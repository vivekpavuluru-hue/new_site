<?php
require_once __DIR__ . '/../models/PurchaseOrderModel.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';

class GrnListController {
    public function index() {
        AuthHelper::requireLogin();

        $model = new PurchaseOrderModel();
        
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Define allowed limit values
        $allowedLimits = [10, 20, 50, 100];
        $recordsPerPage = isset($_GET['limit']) && in_array((int)$_GET['limit'], $allowedLimits) ? (int)$_GET['limit'] : 10;
        
        $offset = ($page - 1) * $recordsPerPage;
        
        $totalRecords = $model->getGrnListCount();
        $totalPages = ceil($totalRecords / $recordsPerPage);
        
        $tableData = $model->getGrnList($recordsPerPage, $offset);
        
        // Load View
        require __DIR__ . '/../views/GrnList.php';
    }
}
?>
