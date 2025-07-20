<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include '../db.php';

$data = json_decode(file_get_contents("php://input"));

$id = $data->id;
$user_id = $data->user_id;
$stock_id = $data->stock_id;
$quantity = $data->quantity;

// Ambil harga baru
$stmt = $conn->prepare("SELECT price_per_unit FROM stock WHERE id = ?");
$stmt->bind_param("i", $stock_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$price_per_unit = $row['price_per_unit'];
$total_price = $price_per_unit * $quantity;

// Update transaksi
$stmt = $conn->prepare("UPDATE transactions SET user_id = ?, stock_id = ?, quantity = ?, total_price = ? WHERE id = ?");
$stmt->bind_param("iiidi", $user_id, $stock_id, $quantity, $total_price, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
?>