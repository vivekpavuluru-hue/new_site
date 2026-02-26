<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Transfers</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="top-bar">
        <h2 class="page-title">
            Stock Transfers
        </h2>
        <a href="index.php?route=logout" class="btn btn-danger">Sign Out</a>
    </div>

    <div class="container">
        <div class="d-flex justify-between align-center mt-2rem mb-2rem">
            <h3 class="record-title">
                Records (<?php echo number_format($totalRecords); ?> found)
            </h3>
            <div class="d-flex gap-15 align-center">
                <form action="index.php?route=stockTransfers" method="POST" id="filterForm" class="filter-form" style="display: flex; align-items: center; gap: 10px;">
                    <input type="hidden" name="page" id="pageInput" value="<?php echo $page; ?>">
                    <div>
                        <label for="limitSelect" class="filter-label">Rows:</label>
                        <select id="limitSelect" name="limit" onchange="document.getElementById('pageInput').value=1; this.form.submit()" class="filter-control">
                            <option value="10" <?php echo $recordsPerPage === 10 ? 'selected' : ''; ?>>10</option>
                            <option value="20" <?php echo $recordsPerPage === 20 ? 'selected' : ''; ?>>20</option>
                            <option value="50" <?php echo $recordsPerPage === 50 ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?php echo $recordsPerPage === 100 ? 'selected' : ''; ?>>100</option>
                            <option value="150" <?php echo $recordsPerPage === 150 ? 'selected' : ''; ?>>150</option>
                        </select>
                    </div>
                </form>
                <a href="index.php?route=dashboard" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>

        <div style="overflow-x: auto;">
        <?php if (empty($tableData['data'])): ?>
            <p>No stock transfers found.</p>
        <?php else: ?>
            <table class="data-table-centered">
                <tr>
                    <?php foreach ($tableData['columns'] as $colName): ?>
                        <th><?php 
                            if ($colName === 'plant_name') echo 'Plant Name';
                            elseif ($colName === 'indent_no') echo 'Indent No';
                            elseif ($colName === 'indent_date') echo 'Indent Date';
                            elseif ($colName === 'approved_status') echo 'Approved Status';
                            elseif ($colName === 'approved_date') echo 'Approved Date';
                            else echo htmlspecialchars(ucwords(str_replace('_', ' ', $colName))); 
                        ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach ($tableData['data'] as $row): ?>
                    <tr>
                        <?php foreach ($row as $colName => $data): ?>
                            <?php 
                            $displayData = $data !== null ? (string)$data : 'NULL'; 
                            ?>
                            <td><?php echo htmlspecialchars($displayData); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination mt-2rem d-flex justify-center gap-15">
                <?php if ($page > 1): ?>
                    <a href="javascript:void(0)" onclick="document.getElementById('pageInput').value=<?php echo $page - 1; ?>; document.getElementById('filterForm').submit();" class="page-link">&laquo; Previous</a>
                <?php endif; ?>
                
                <span class="page-info">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                
                <?php if ($page < $totalPages): ?>
                    <a href="javascript:void(0)" onclick="document.getElementById('pageInput').value=<?php echo $page + 1; ?>; document.getElementById('filterForm').submit();" class="page-link">Next &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
