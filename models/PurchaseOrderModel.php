<?php
require_once __DIR__ . '/Database.php';

class PurchaseOrderModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getRecordsCount($table, $status) {
        $whereClause = "";
        if ($status !== 'all') {
            $whereClause = " WHERE status = '" . $this->conn->real_escape_string($status) . "'";
        }
        
        $query = "SELECT COUNT(*) FROM `" . $this->conn->real_escape_string($table) . "` $whereClause";
        $result = $this->conn->query($query);
        
        return $result ? (int)$result->fetch_row()[0] : 0;
    }

    public function getRecords($table, $status, $limit, $offset) {
        $whereClause = "";
        if ($status !== 'all') {
            $whereClause = " WHERE status = '" . $this->conn->real_escape_string($status) . "'";
        }

        $query = "SELECT * FROM `" . $this->conn->real_escape_string($table) . "` $whereClause LIMIT $offset, $limit";
        $result = $this->conn->query($query);
        
        $records = [];
        $columns = [];
        
        if ($result && $result->num_rows > 0) {
            $fieldinfo = $result->fetch_fields();
            foreach ($fieldinfo as $val) {
                $columns[] = $val->name;
            }
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
        }
        
        return [
            'columns' => $columns,
            'data' => $records
        ];
    }

    public function getCustomOpenPOs($limit, $offset) {
        // Fetch po_id as well to make rows clickable, but we'll hide it in the view
        $query = "
            SELECT 
                po.po_id AS `_po_id`,
                po.po_number AS `PO No`, 
                po.po_date AS `PO Date`, 
                v.vendor_name AS `Party Name`
            FROM `purchase_ordersv1` po
            LEFT JOIN `vendors` v ON po.vendor_id = v.vendor_id
            WHERE po.status = 'open'
            LIMIT $offset, $limit
        ";
        
        $result = $this->conn->query($query);
        
        $records = [];
        $columns = [];
        
        if ($result && $result->num_rows > 0) {
            $fieldinfo = $result->fetch_fields();
            foreach ($fieldinfo as $val) {
                // Don't add the hidden ID to the display columns
                if ($val->name !== '_po_id') {
                    $columns[] = $val->name;
                }
            }
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
        }
        
        return [
            'columns' => $columns,
            'data' => $records
        ];
    }

    public function getPoDetails($poId) {
        $id = $this->conn->real_escape_string($poId);
        $query = "
            SELECT po.*, v.vendor_name 
            FROM `purchase_ordersv1` po
            LEFT JOIN `vendors` v ON po.vendor_id = v.vendor_id
            WHERE po.po_id = '$id'
        ";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_assoc() : null;
    }

    public function getPoItems($poId) {
        $id = $this->conn->real_escape_string($poId);
        // Include item name and description from items table using item_id
        $query = "
            SELECT poi.*, itm.item_name, itm.item_description 
            FROM `purchase_order_items` poi
            LEFT JOIN `items` itm ON poi.item_id = itm.item_id
            WHERE poi.po_id = '$id'
        ";
        $result = $this->conn->query($query);
        $items = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        }
        return $items;
    }

    public function createGrn($poId, $items) {
        $poIdSafe = $this->conn->real_escape_string($poId);
        $now = date('Y-m-d H:i:s');
        
        $totalQty = 0;
        foreach ($items as $item) {
            $totalQty += $item['received_qty'];
        }

        // Get next grn_id because AUTO_INCREMENT is missing
        $grnIdResult = $this->conn->query("SELECT MAX(grn_id) FROM `grn_main`");
        $nextGrnId = 1;
        if ($grnIdResult && $row = $grnIdResult->fetch_row()) {
            $nextGrnId = ((int)$row[0]) + 1;
        }
        
        // Assuming minimal fields needed. Adjust as required.
        $query = "INSERT INTO `grn_main` 
                  (grn_id, po_id_ref, total_received_qty, grn_received_qty, created_at, grn_type, tcs, other_charges, grn_status, focus9_status, discount_after_tax, agent_name, focus9_tds_amount, focus9_tds_status, boe_no, boe_value, boe_basicvalue, boe_customdutyper, pr_status, discount_type, fr_in, fr_out, gross_wt, tier_wt, net_wt, lr_status, mjobwork_id, dc_status, is_full_filled) 
                  VALUES 
                  ('$nextGrnId', '$poIdSafe', '$totalQty', '$totalQty', '$now', 'purchase_ordersv1', '0', '0', 0, '0', '0', 'DIRECT', 0.000, 0, '0', '0', '0', '0', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'N')";
        
        if ($this->conn->query($query)) {
            $grnId = $nextGrnId;
            
            // Get next grn_item_id
            $grnItemIdResult = $this->conn->query("SELECT MAX(grn_item_id) FROM `grn_items_list`");
            $nextItemId = 1;
            if ($grnItemIdResult && $row = $grnItemIdResult->fetch_row()) {
                $nextItemId = ((int)$row[0]) + 1;
            }

            foreach ($items as $item) {
                $itemId = $this->conn->real_escape_string($item['item_id'] ?? 0);
                $poItemId = $this->conn->real_escape_string($item['po_item_id'] ?? 0);
                $poItemPrice = $this->conn->real_escape_string($item['po_item_price'] ?? 0);
                $poItemQty = $this->conn->real_escape_string($item['po_item_qty'] ?? 0);
                $receivedQty = $this->conn->real_escape_string($item['received_qty'] ?? 0);
                
                $itemQuery = "INSERT INTO `grn_items_list` 
                               (grn_item_id, grn_id, po_id, po_item_id, item_id, po_item_price, po_item_qty, received_qty, created_at, is_active, dept_pass, currency_rate, import_cost, item_wt, total_wt) 
                               VALUES 
                               ('$nextItemId', '$grnId', '$poIdSafe', '$poItemId', '$itemId', '$poItemPrice', '$poItemQty', '$receivedQty', '$now', 'active', 'no', 1, 0, '0', 0)";
                $this->conn->query($itemQuery);
                $nextItemId++;
            }
            return $grnId;
        }
        return false;
    }

    public function getGrnListCount() {
        $query = "SELECT COUNT(*) FROM `grn_main`";
        $result = $this->conn->query($query);
        return $result ? (int)$result->fetch_row()[0] : 0;
    }

    public function getGrnList($limit, $offset) {
        $query = "
            SELECT 
                g.grn_id AS `GRN ID`,
                po.po_number AS `PO No`,
                v.vendor_name AS `Party Name`,
                g.total_received_qty AS `Total Qty`,
                g.created_at AS `Created Date`
            FROM `grn_main` g
            LEFT JOIN `purchase_ordersv1` po ON g.po_id_ref = po.po_id
            LEFT JOIN `vendors` v ON po.vendor_id = v.vendor_id
            ORDER BY g.created_at DESC
            LIMIT $offset, $limit
        ";
        
        $result = $this->conn->query($query);
        $records = [];
        $columns = [];
        
        if ($result && $result->num_rows > 0) {
            $fieldinfo = $result->fetch_fields();
            foreach ($fieldinfo as $val) {
                $columns[] = $val->name;
            }
            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }
        }
        
        return [
            'columns' => $columns,
            'data' => $records
        ];
    }
}
?>
