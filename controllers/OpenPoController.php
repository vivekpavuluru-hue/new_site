<?php
require_once __DIR__ . '/../models/PurchaseOrderModel.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';

class OpenPoController {
    
    private $model;

    public function __construct() {
        $this->model = new PurchaseOrderModel();
    }

    public function index() {
        AuthHelper::requireLogin();

        // Hardcode the table and status since this is a dedicated Open POs page
        $selectedTable = 'purchase_ordersv1';
        $selectedStatus = 'open';

        // Pagination
        $allowedLimits = [10, 20, 50, 100];
        $recordsPerPage = isset($_GET['limit']) && in_array((int)$_GET['limit'], $allowedLimits) ? (int)$_GET['limit'] : 10;

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $recordsPerPage;

        // Fetch Data from Model
        $totalRecords = $this->model->getRecordsCount($selectedTable, $selectedStatus);
        $totalPages = ceil($totalRecords / $recordsPerPage);
        
        // Fetch customized data
        $tableData = $this->model->getCustomOpenPOs($recordsPerPage, $offset);

        // Load the view and pass data to it dynamically
        require_once __DIR__ . '/../views/OpenPos.php';
    }
}
?>
