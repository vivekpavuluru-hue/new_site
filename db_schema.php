<?php
require_once __DIR__ . '/config/database.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

$query = "
    SELECT 
        si.indent_no,
        CASE 
            WHEN COUNT(mrr.approval_status) = 0 THEN 'Pending'
            WHEN SUM(CASE WHEN mrr.approval_status = 'approval_pending' THEN 1 ELSE 0 END) > 0 THEN 'Pending'
            WHEN SUM(CASE WHEN mrr.approval_status = 'rejected' THEN 1 ELSE 0 END) > 0 THEN 'Rejected'
            WHEN SUM(CASE WHEN mrr.approval_status = 'approved' THEN 1 ELSE 0 END) = COUNT(mrr.approval_status) THEN 'Approved'
            ELSE 'Unknown'
        END AS overall_status,
        GROUP_CONCAT(
            CONCAT(mr.name, ' (', 
                CASE 
                    WHEN mrr.approval_status = 'approval_pending' THEN 'Pending'
                    WHEN mrr.approval_status = 'approved' THEN 'APPROVED'
                    WHEN mrr.approval_status = 'rejected' THEN 'REJECTED'
                    ELSE UPPER(mrr.approval_status)
                END, 
            ')') SEPARATOR ', '
        ) AS approval_details
    FROM `stocktransfer_indents` si
    LEFT JOIN `mail_requisitions_responses` mrr ON si.indent_no = mrr.indent_no
    LEFT JOIN `mail_requisitions` mr ON mrr.mail_requisition_id = mr.mail_requisition_id
    GROUP BY si.indent_no
    LIMIT 10
";

$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "Error: " . $conn->error;
}
