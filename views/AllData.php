<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Data Records</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <div class="d-flex justify-between align-center mt-2rem mb-2rem">
            <form action="index.php" method="GET" class="filter-form">
                <input type="hidden" name="route" value="allData">
                <div>
                    <select id="tableSelect" name="table" onchange="this.form.submit()" class="filter-control">
                        <option value="purchase_ordersv1" <?php echo $selectedTable === 'purchase_ordersv1' ? 'selected' : ''; ?>>Purchase Orders</option>
                        <option value="purchase_order_items" <?php echo $selectedTable === 'purchase_order_items' ? 'selected' : ''; ?>>Purchase Order Items</option>
                    </select>
                </div>
                <div>
                    <label for="statusSelect" class="filter-label">Status:</label>
                    <select id="statusSelect" name="status" onchange="this.form.submit()" class="filter-control">
                        <option value="all" <?php echo $selectedStatus === 'all' ? 'selected' : ''; ?>>All</option>
                        <option value="open" <?php echo $selectedStatus === 'open' ? 'selected' : ''; ?>>Open</option>
                        <option value="close" <?php echo $selectedStatus === 'close' ? 'selected' : ''; ?>>Close</option>
                    </select>
                </div>
                <div>
                    <label for="limitSelect" class="filter-label">Rows:</label>
                    <select id="limitSelect" name="limit" onchange="this.form.submit()" class="filter-control">
                        <option value="10" <?php echo $recordsPerPage === 10 ? 'selected' : ''; ?>>10</option>
                        <option value="20" <?php echo $recordsPerPage === 20 ? 'selected' : ''; ?>>20</option>
                        <option value="50" <?php echo $recordsPerPage === 50 ? 'selected' : ''; ?>>50</option>
                        <option value="100" <?php echo $recordsPerPage === 100 ? 'selected' : ''; ?>>100</option>
                    </select>
                </div>
                <?php if (isset($_GET['page'])): ?>
                    <input type="hidden" name="page" value="1"> <!-- Reset to page 1 on filter change -->
                <?php endif; ?>
            </form>
            
            <div class="d-flex gap-15 align-center">
                <a href="index.php?route=dashboard" class="btn btn-primary">Back to Dashboard</a>
                <a href="index.php?route=logout" class="btn btn-danger">Sign Out</a>
            </div>
        </div>

        <h2 class="page-title">
            <?php echo htmlspecialchars($selectedTable === 'purchase_ordersv1' ? 'Purchase Orders' : 'Purchase Order Items'); ?>
            <?php if ($selectedStatus !== 'all') echo " (" . ucfirst(htmlspecialchars($selectedStatus)) . ")"; ?>
            <span class="record-count">
                (<?php echo number_format($totalRecords); ?> records found)
            </span>
        </h2>
        
        <div style="overflow-x: auto;">
        <?php if (!empty($tableData['data'])): ?>
            <table class="data-table-centered">
                <tr>
                    <?php foreach ($tableData['columns'] as $col): ?>
                        <th><?php echo htmlspecialchars($col); ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach ($tableData['data'] as $row): ?>
                    <tr>
                        <?php foreach ($row as $data): ?>
                            <?php $displayData = $data !== null ? (string)$data : 'NULL'; ?>
                            <td><?php echo htmlspecialchars($displayData); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No records found in this table for the selected status.</p>
        <?php endif; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?route=allData&table=<?php echo urlencode($selectedTable); ?>&status=<?php echo urlencode($selectedStatus); ?>&limit=<?php echo $recordsPerPage; ?>&page=<?php echo $page - 1; ?>" class="page-link">&laquo; Previous</a>
                <?php endif; ?>
                
                <span class="page-info">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?route=allData&table=<?php echo urlencode($selectedTable); ?>&status=<?php echo urlencode($selectedStatus); ?>&limit=<?php echo $recordsPerPage; ?>&page=<?php echo $page + 1; ?>" class="page-link">Next &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
