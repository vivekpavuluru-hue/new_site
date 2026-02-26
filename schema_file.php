<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'new_site_db', 3307);
if ($conn->connect_error) {
    file_put_contents('schema_out.txt', "Connection failed: " . $conn->connect_error);
    exit;
}
$out = "";
$tables = ['items', 'item_db', 'purchase_order_items', 'purchase_ordersv1', 'item_tax', 'item_pricing', 'grn_items_list'];
foreach($tables as $table) {
    $out .= "Table: $table\n";
    $result = $conn->query("SHOW COLUMNS FROM `$table`");
    if($result) {
        while($row = $result->fetch_assoc()) {
            $out .= "  " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } else {
        $out .= "  Table not found or error: " . $conn->error . "\n";
    }
}
file_put_contents('schema_out.txt', $out);
echo "Done";
?>
