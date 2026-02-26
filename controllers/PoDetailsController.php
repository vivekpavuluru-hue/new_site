<?php
require_once __DIR__ . '/../models/PurchaseOrderModel.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';

class PoDetailsController {
    
    private $model;

    public function __construct() {
        $this->model = new PurchaseOrderModel();
    }

    public function index() {
        AuthHelper::requireLogin();

        if (!isset($_GET['po_id'])) {
            // Redirect back if no PO ID provided
            header("Location: index.php?route=openPos");
            exit;
        }

        $poId = $_GET['po_id'];

        $poDetails = $this->model->getPoDetails($poId);
        $poItems = $this->model->getPoItems($poId);

        if (!$poDetails) {
            echo "Purchase Order not found.";
            exit;
        }

        require_once __DIR__ . '/../views/PoDetails.php';
    }
}
?>
