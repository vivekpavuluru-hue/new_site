<?php
require_once __DIR__ . '/models/Database.php';
$conn = Database::getInstance()->getConnection();

$query = "SELECT mrr.approval_status, COUNT(*) as count 
          FROM `stocktransfer_indents` si
          LEFT JOIN `mail_requisitions_responses` mrr 
              ON si.indent_no = mrr.indent_no
          GROUP BY mrr.approval_status";

$result = $conn->query($query);
echo "Approval Statuses:\n";
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
?>
