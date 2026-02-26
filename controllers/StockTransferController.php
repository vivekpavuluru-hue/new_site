<?php
require_once __DIR__ . '/../models/StockTransferModel.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';

class StockTransferController {
    
    private $model;

    public function __construct() {
        $this->model = new StockTransferModel();
    }

    public function index() {
        AuthHelper::requireLogin();

        // Pagination
        $allowedLimits = [10, 20, 50, 100, 150];
        $recordsPerPage = isset($_POST['limit']) && in_array((int)$_POST['limit'], $allowedLimits) ? (int)$_POST['limit'] : 10;

        $page = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
        $offset = ($page - 1) * $recordsPerPage;

        // Fetch Data from Model
        $totalRecords = $this->model->getRecordsCount();
        $totalPages = ceil($totalRecords / $recordsPerPage);
        if ($totalPages == 0) $totalPages = 1;

        // Fetch customized data
        $tableData = $this->model->getCustomStockTransfers($recordsPerPage, $offset);

        // Load the view and pass data to it dynamically
        require_once __DIR__ . '/../views/StockTransfers.php';
    }
}
?>
