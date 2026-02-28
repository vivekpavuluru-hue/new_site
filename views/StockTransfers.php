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
                            elseif ($colName === 'overall_status') echo 'Overall Status';
                            elseif ($colName === 'approval_details') echo 'Approval Details';
                            elseif ($colName === 'approved_date') echo 'Approved Date';
                            else echo htmlspecialchars(ucwords(str_replace('_', ' ', $colName))); 
                        ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach ($tableData['data'] as $row): ?>
                    <tr>
                        <?php foreach ($row as $colName => $data): ?>
                            <?php if ($colName === 'approval_details' && $data): ?>
                                <td style="text-align: left; vertical-align: middle;">
                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                        <?php foreach (explode('||', $data) as $approver): ?>
                                            <?php 
                                            // Extract status for styling
                                            $approverText = htmlspecialchars(trim($approver));
                                            $statusHtml = '';
                                            $bgColor = '#f8f9fa';
                                            $borderColor = '#e9ecef';
                                            
                                            // Extract embedded date if present
                                            $dateText = '';
                                            $dashPos = strrpos($approverText, ' - ');
                                            if ($dashPos !== false && $dashPos > strrpos($approverText, ')')) {
                                                $dateText = trim(substr($approverText, $dashPos + 3));
                                                $approverText = trim(substr($approverText, 0, $dashPos));
                                            }
                                            
                                            // Determine styles based on status string
                                            if (strpos($approverText, '(APPROVED)') !== false) {
                                                $approverText = str_replace('(APPROVED)', '', $approverText);
                                                $statusHtml = '<span style="background-color: #d1e7dd; color: #0f5132; padding: 2px 8px; border-radius: 12px; font-size: 0.8em; font-weight: 600; white-space: nowrap;">✓ Approved</span>';
                                                $bgColor = '#f4fbf7';
                                                $borderColor = '#badbcc';
                                            } elseif (strpos($approverText, '(Pending)') !== false) {
                                                $approverText = str_replace('(Pending)', '', $approverText);
                                                $statusHtml = '<span style="background-color: #fff3cd; color: #664d03; padding: 2px 8px; border-radius: 12px; font-size: 0.8em; font-weight: 600; white-space: nowrap;">⏳ Pending</span>';
                                                $bgColor = '#fffdf3';
                                                $borderColor = '#ffecb5';
                                            } elseif (strpos($approverText, '(REJECTED)') !== false) {
                                                $approverText = str_replace('(REJECTED)', '', $approverText);
                                                $statusHtml = '<span style="background-color: #f8d7da; color: #842029; padding: 2px 8px; border-radius: 12px; font-size: 0.8em; font-weight: 600; white-space: nowrap;">✗ Rejected</span>';
                                                $bgColor = '#fdf4f5';
                                                $borderColor = '#f5c2c7';
                                            }
                                            ?>
                                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 12px; background-color: <?php echo $bgColor; ?>; border: 1px solid <?php echo $borderColor; ?>; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); min-width: 220px;">
                                                <div style="display: flex; flex-direction: column; margin-right: 15px;">
                                                    <span style="font-size: 0.9em; font-weight: 500; color: #495057; line-height: 1.2;"><?php echo trim($approverText); ?></span>
                                                    <?php if ($dateText): ?>
                                                        <span style="font-size: 0.75em; color: #6c757d; margin-top: 4px;"><span style="opacity: 0.7;">🕒</span> <?php echo $dateText; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php echo $statusHtml; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                            <?php elseif ($colName === 'overall_status'): ?>
                                <td style="vertical-align: middle;">
                                    <?php 
                                    $statusText = htmlspecialchars((string)$data);
                                    if ($statusText === 'Approved') {
                                        echo '<span style="display: inline-block; background-color: #d1e7dd; color: #0f5132; padding: 5px 12px; border-radius: 20px; font-size: 0.85em; font-weight: 600;">✓ Approved</span>';
                                    } elseif ($statusText === 'Pending') {
                                        echo '<span style="display: inline-block; background-color: #fff3cd; color: #664d03; padding: 5px 12px; border-radius: 20px; font-size: 0.85em; font-weight: 600;">⏳ Pending</span>';
                                    } elseif ($statusText === 'Rejected') {
                                        echo '<span style="display: inline-block; background-color: #f8d7da; color: #842029; padding: 5px 12px; border-radius: 20px; font-size: 0.85em; font-weight: 600;">✗ Rejected</span>';
                                    } else {
                                        echo '<span style="display: inline-block; background-color: #e9ecef; color: #495057; padding: 5px 12px; border-radius: 20px; font-size: 0.85em; font-weight: 600;">' . $statusText . '</span>';
                                    }
                                    ?>
                                </td>
                            <?php else: ?>
                                <?php $displayData = $data !== null ? (string)$data : 'NULL'; ?>
                                <td><?php echo htmlspecialchars($displayData); ?></td>
                            <?php endif; ?>
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
