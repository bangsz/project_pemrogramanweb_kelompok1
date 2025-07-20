<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include '../db.php';

$query = "
SELECT t.*, u.name AS user_name, s.item_name AS stock_name
FROM transactions t
JOIN users u ON u.id = t.user_id
JOIN stock s ON s.id = t.stock_id
ORDER BY transaction_date DESC
";

$result = $conn->query($query);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>