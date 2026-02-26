<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="top-bar">
        <h2 class="page-title">Dashboard</h2>
        <a href="index.php?route=logout" class="btn btn-danger">Sign Out</a>
    </div>

    <div class="container">
        <div class="dashboard-cards">
            <!-- Open POs Card -->
            <div class="card">
                <h3>Total Open PO</h3>
                <div class="count"><?php echo htmlspecialchars($openPoCount ?? '--'); ?></div>
                <a href="index.php?route=openPos" class="btn btn-info mt-2rem">View Open POs</a>
            </div>
            
            <!-- All Data Card -->
            <div class="card">
                <h3>All Records</h3>
                <div class="count muted">--</div>
                <a href="index.php?route=allData" class="btn btn-secondary mt-2rem">View All Data</a>
            </div>

            <!-- Stock Transfer Card -->
            <div class="card"> 
                <h3>Stock Transfers</h3>
                <div class="count"><?php echo htmlspecialchars($openStockTransferCount ?? '--'); ?></div>
                <a href="index.php?route=stockTransfers" class="btn btn-warning mt-2rem" style="color: black;">View Stock Transfers</a>
            </div>
        </div>
    </div>
</body>
</html>