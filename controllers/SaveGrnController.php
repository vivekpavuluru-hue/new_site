<?php
require_once __DIR__ . '/../models/PurchaseOrderModel.php';
require_once __DIR__ . '/../helpers/AuthHelper.php'; // Added this line for AuthHelper

class SaveGrnController {
    public function save() {
        AuthHelper::requireLogin(); // Added this line

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $poId = $_POST['po_id'] ?? null; // Renamed $po_id to $poId
            $items = [];

            if (isset($_POST['received_qty']) && is_array($_POST['received_qty'])) {
                foreach ($_POST['received_qty'] as $index => $qty) {
                    if ($qty > 0) { // Only process items with received quantity > 0
                        $items[] = [
                            'item_id' => $_POST['item_id'][$index] ?? null,
                            'po_item_id' => $_POST['po_item_id'][$index] ?? null,
                            'po_item_price' => $_POST['po_item_price'][$index] ?? 0,
                            'po_item_qty' => $_POST['po_item_qty'][$index] ?? 0,
                            'received_qty' => $qty
                        ];
                    }
                }
            }

            if ($poId && !empty($items)) { // Renamed $po_id to $poId
                $model = new PurchaseOrderModel();

                $grnId = $model->createGrn($poId, $items); // Renamed $grn_id to $grnId and $po_id to $poId
                
                if ($grnId) { // Renamed $grn_id to $grnId
                    header("Location: index.php?route=poDetails&po_id=" . urlencode($poId) . "&success=1"); // Renamed $po_id to $poId
                    exit;
                }
            }

            // Redirect back if failure
            header("Location: index.php?route=poDetails&po_id=" . urlencode($poId) . "&error=1"); // Renamed $po_id to $poId
            exit;
        } else {
            header("Location: index.php?route=openPos");
            exit;
        }
    }
}
?>
