<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

include '../db.php';

$data = json_decode(file_get_contents("php://input"));

$id = $data->id;
$name = $data->name;
$email = $data->email;
$message = $data->message;

$stmt = $conn->prepare("UPDATE contacts SET name = ?, email = ?, message = ? WHERE id = ?");
$stmt->bind_param("sssi", $name, $email, $message, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
?>