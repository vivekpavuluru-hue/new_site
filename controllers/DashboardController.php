<?php
require_once __DIR__ . '/../models/PurchaseOrderModel.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';

class DashboardController {
    
    private $model;

    public function __construct() {
        $this->model = new PurchaseOrderModel();
    }

    public function index() {
        AuthHelper::requireLogin();

        // Fetch Total Open PO Count for the Information Button
        $openPoCount = $this->model->getRecordsCount('purchase_ordersv1', 'open');

        // Fetch Open Stock Transfers Count
        require_once __DIR__ . '/../models/StockTransferModel.php';
        $stModel = new StockTransferModel();
        $openStockTransferCount = $stModel->getOpenRecordsCount();

        // Load the view and pass data to it dynamically
        require_once __DIR__ . '/../views/Dashboard.php';
    }
}
?>
