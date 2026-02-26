<?php
// index.php acts as the Front Controller for the MVC structure

// Autoloading could be set up here, but we'll require manually for simplicity
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';

// Parse the route from the URL (default to login)
$route = $_GET['route'] ?? 'login';

// Basic router switch
switch ($route) {
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;

    case 'openPos':
        require_once __DIR__ . '/controllers/OpenPoController.php';
        $controller = new OpenPoController();
        $controller->index();
        break;

    case 'poDetails':
        require_once __DIR__ . '/controllers/PoDetailsController.php';
        $controller = new PoDetailsController();
        $controller->index();
        break;

    case 'allData':
        require_once __DIR__ . '/controllers/AllDataController.php';
        $controller = new AllDataController();
        $controller->index();
        break;

    case 'saveGrn':
        require_once __DIR__ . '/controllers/SaveGrnController.php';
        $controller = new SaveGrnController();
        $controller->save();
        break;

    case 'grnList':
        require_once __DIR__ . '/controllers/GrnListController.php';
        $controller = new GrnListController();
        $controller->index();
        break;

    case 'stockTransfers':
        require_once __DIR__ . '/controllers/StockTransferController.php';
        $controller = new StockTransferController();
        $controller->index();
        break;

    default:
        // 404 Not Found fallback
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found - The requested route '$route' does not exist.";
        break;
}
?>