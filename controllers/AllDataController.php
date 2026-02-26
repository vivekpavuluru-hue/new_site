<?php
require_once __DIR__ . '/../models/PurchaseOrderModel.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';

class AllDataController {
    
    private $model;

    public function __construct() {
        $this->model = new PurchaseOrderModel();
    }

    public function index() {
        AuthHelper::requireLogin();

        $allowedTables = ['purchase_ordersv1', 'purchase_order_items'];
        $selectedTable = isset($_GET['table']) && in_array($_GET['table'], $allowedTables) ? $_GET['table'] : 'purchase_ordersv1';

        $allowedStatuses = ['all', 'open', 'close']; 
        $selectedStatus = isset($_GET['status']) && in_array($_GET['status'], $allowedStatuses) ? $_GET['status'] : 'all';

        $allowedLimits = [10, 20, 50, 100];
        $recordsPerPage = isset($_GET['limit']) && in_array((int)$_GET['limit'], $allowedLimits) ? (int)$_GET['limit'] : 10;

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $recordsPerPage;

        $totalRecords = $this->model->getRecordsCount($selectedTable, $selectedStatus);
        $totalPages = ceil($totalRecords / $recordsPerPage);
        
        $tableData = $this->model->getRecords($selectedTable, $selectedStatus, $recordsPerPage, $offset);

        require_once __DIR__ . '/../views/AllData.php';
    }
}
?>
