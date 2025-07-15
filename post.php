<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include '../db.php';

$data = json_decode(file_get_contents("php://input"));

$item_name = $data->item_name;
$quantity = $data->quantity;
$unit = $data->unit;
$price_per_unit = $data->price_per_unit;

$stmt = $conn->prepare("INSERT INTO stock (item_name, quantity, unit, price_per_unit) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sisd", $item_name, $quantity, $unit, $price_per_unit);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "id" => $stmt->insert_id]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
?>