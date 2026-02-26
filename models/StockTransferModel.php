<?php
require_once __DIR__ . '/Database.php';

class StockTransferModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getRecordsCount() {
        // Capped at 150 rows.
        // If the query returns 170, we'll only do 150.
        $query = "SELECT COUNT(*) FROM `stocktransfer_indents`";
        $result = $this->conn->query($query);
        
        $count = $result ? (int)$result->fetch_row()[0] : 0;
        return min($count, 150);
    }

    public function getOpenRecordsCount() {
        $query = "
            SELECT COUNT(DISTINCT si.indent_no) 
            FROM `stocktransfer_indents` si
            LEFT JOIN `mail_requisitions_responses` mrr 
                ON si.indent_no = mrr.indent_no
            WHERE mrr.approval_status = 'approval_pending' OR mrr.approval_status IS NULL
        ";
        $result = $this->conn->query($query);
        return $result ? (int)$result->fetch_row()[0] : 0;
    }

    public function getCustomStockTransfers($limit, $offset) {
        // Limit query to only pull a MAXIMUM of 150 rows total.
        // We do this by adjusting the limit down if the user requests an offset+limit > 150
        if ($offset >= 150) {
            return ['columns' => [], 'data' => []];
        }
        
        if ($offset + $limit > 150) {
            $limit = 150 - $offset;
        }

        // Fetch plant name, indent_no, indent_date, approved_status, approved_date
        $query = "
            SELECT 
                p.plant_name AS `plant_name`,
                si.indent_no AS `indent_no`,
                DATE_FORMAT(si.indent_date, '%Y-%m-%d') AS `indent_date`,
                mrr.approval_status AS `approved_status`,
                CASE 
                    WHEN mrr.approval_status = 'approval_pending' THEN 'Pending'
                    ELSE mr.name 
                END AS `approved_by`,
                DATE_FORMAT(MAX(mrr.updated_at), '%Y-%m-%d') AS `approved_date`
            FROM `stocktransfer_indents` si
            LEFT JOIN `mail_requisitions_responses` mrr 
                ON si.indent_no = mrr.indent_no
            LEFT JOIN `mail_requisitions` mr
                ON mrr.mail_requisition_id = mr.mail_requisition_id
            LEFT JOIN `company_db`.`plants` p 
                ON mr.plant_id = p.plant_id
            GROUP BY si.indent_no
            ORDER BY si.created_at DESC
            LIMIT $offset, $limit
        ";
        
        $result = $this->conn->query($query);
        
        $records = [];
        $columns = ['plant_name', 'indent_no', 'indent_date', 'approved_status', 'approved_by', 'approved_date'];
        
        if ($result && $result->num_rows > 0) {
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
