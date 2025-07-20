<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include '../db.php';

$data = json_decode(file_get_contents("php://input"));

$user_id = $data->user_id;
$stock_id = $data->stock_id;
$quantity = $data->quantity;

// Ambil harga dari stock
$stmt = $conn->prepare("SELECT price_per_unit FROM stock WHERE id = ?");
$stmt->bind_param("i", $stock_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$price_per_unit = $row['price_per_unit'];
$total_price = $price_per_unit * $quantity;

// Simpan transaksi
$stmt = $conn->prepare("INSERT INTO transactions (user_id, stock_id, quantity, total_price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiid", $user_id, $stock_id, $quantity, $total_price);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "id" => $stmt->insert_id]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
?>