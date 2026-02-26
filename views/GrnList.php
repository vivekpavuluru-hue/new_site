<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GRN List</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <div class="d-flex justify-between align-center mt-2rem mb-2rem">
            <h2 class="page-title">
                Goods Receipt Notes (GRN)
                <span class="record-count">
                    (<?php echo number_format($totalRecords); ?> records found)
                </span>
            </h2>
            <div class="d-flex gap-15 align-center">
                <?php if (isset($_GET['success'])): ?>
                    <span style="color: green; font-weight: bold;">GRN Created Successfully!</span>
                <?php endif; ?>
                <form action="index.php" method="GET" class="filter-form">
                    <input type="hidden" name="route" value="grnList">
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
                        <input type="hidden" name="page" value="1">
                    <?php endif; ?>
                </form>
                <a href="index.php?route=dashboard" class="btn btn-primary">Back to Dashboard</a>
                <a href="index.php?route=logout" class="btn btn-danger">Sign Out</a>
            </div>
        </div>

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
                        <?php foreach ($row as $colName => $data): ?>
                            <td><?php echo htmlspecialchars($data !== null ? (string)$data : 'NULL'); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No GRN records found.</p>
        <?php endif; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?route=grnList&limit=<?php echo $recordsPerPage; ?>&page=<?php echo $page - 1; ?>" class="page-link">&laquo; Previous</a>
                <?php endif; ?>
                
                <span class="page-info">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?route=grnList&limit=<?php echo $recordsPerPage; ?>&page=<?php echo $page + 1; ?>" class="page-link">Next &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
