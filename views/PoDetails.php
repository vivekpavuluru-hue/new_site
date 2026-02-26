<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PO Details</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="text-right mb-15 clearfix">
        <a href="index.php?route=logout" class="btn btn-danger">Sign Out</a>
    </div>

    <div class="container-box">
        <div class="header-actions">
            <h2 class="page-title">Purchase Order Details</h2>
            <a href="index.php?route=openPos" class="btn btn-secondary">&laquo; Back to Open POs</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: bold;">
                Saved successfully!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: bold;">
                Failed to save the record.
            </div>
        <?php endif; ?>

        <div class="po-info-grid">
            <div class="po-info-item">
                <strong>PO Number</strong>
                <span><?php echo htmlspecialchars($poDetails['po_number']); ?></span>
            </div>
            <div class="po-info-item">
                <strong>PO Date</strong>
                <span><?php echo htmlspecialchars($poDetails['po_date']); ?></span>
            </div>
            <div class="po-info-item">
                <strong>Party Name</strong>
                <span><?php echo htmlspecialchars($poDetails['vendor_name'] ?? 'N/A'); ?></span>
            </div>
            <div class="po-info-item">
                <strong>Status</strong>
                <span><?php echo ucfirst(htmlspecialchars($poDetails['status'])); ?></span>
            </div>
        </div>

        <h3 class="section-title">Order Items</h3>
        
        <div style="overflow-x: auto;">
        <?php if (!empty($poItems)): ?>
            <form action="index.php?route=saveGrn" method="post">
                <input type="hidden" name="po_id" value="<?php echo htmlspecialchars($poDetails['po_id']); ?>">
                <table class="data-table">
                    <tr>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Taxable Amount</th>
                        <th>Order Qty</th>
                        <th>Received Qty</th>
                    </tr>
                    <?php foreach ($poItems as $item): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($item['item_name'] ?? ''); ?>
                                <?php if (!empty($item['item_description'])): ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($item['item_description']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars(number_format((float)($item['po_price'] ?? 0), 2)); ?></td>
                            <td><?php echo htmlspecialchars(number_format((float)($poDetails['taxable_amount'] ?? 0), 2)); ?></td>
                            <td><?php echo htmlspecialchars($item['po_qty'] ?? ''); ?></td>
                            <td>
                                <input type="hidden" name="item_id[]" value="<?php echo htmlspecialchars($item['item_id'] ?? ''); ?>">
                                <input type="hidden" name="po_item_id[]" value="<?php echo htmlspecialchars($item['po_item_id'] ?? ''); ?>">
                                <input type="hidden" name="po_item_price[]" value="<?php echo htmlspecialchars($item['po_price'] ?? 0); ?>">
                                <input type="hidden" name="po_item_qty[]" value="<?php echo htmlspecialchars($item['po_qty'] ?? 0); ?>">
                                <input type="number" step="0.01" name="received_qty[]" class="form-control" required min="0" max="<?php echo htmlspecialchars($item['po_qty'] ?? 0); ?>" style="width: 150px; padding: 5px;">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div style="margin-top: 20px; text-align: right;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-size: 16px;">Submit</button>
                </div>
            </form>
        <?php else: ?>
            <p>No items found for this Purchase Order.</p>
        <?php endif; ?>
        </div>
    </div>
</body>
</html>
