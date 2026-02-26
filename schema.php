<?php
require_once __DIR__ . '/models/Database.php';
$conn = Database::getInstance()->getConnection();

$tables = ['items', 'item_db', 'purchase_order_items', 'purchase_ordersv1', 'item_tax', 'item_pricing'];
foreach($tables as $table) {
    echo "Table: $table\n";
    $result = $conn->query("SHOW COLUMNS FROM `$table`");
    if($result) {
        while($row = $result->fetch_assoc()) {
            echo "  " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } else {
        echo "  Table not found or error: " . $conn->error . "\n";
    }
}
?>
