<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include '../db.php';

$data = json_decode(file_get_contents("php://input"));

$id = $data->id;
$item_name = $data->item_name;
$quantity = $data->quantity;
$unit = $data->unit;
$price_per_unit = $data->price_per_unit;

$stmt = $conn->prepare("UPDATE stock SET item_name = ?, quantity = ?, unit = ?, price_per_unit = ? WHERE id = ?");
$stmt->bind_param("sisdi", $item_name, $quantity, $unit, $price_per_unit, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
?>