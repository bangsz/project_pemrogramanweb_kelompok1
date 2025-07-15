<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
// Koneksi database
include '../db.php';

// Ambil dan decode JSON input
$data = json_decode(file_get_contents("php://input"));

// Validasi input
if (!$data || !isset($data->name, $data->email, $data->message)) {
    echo json_encode([
        "status" => "error",
        "message" => "Data tidak lengkap atau format salah. Harus ada name, email, dan message."
    ]);
    exit;
}

$name = $data->name;
$email = $data->email;
$message = $data->message;

// Siapkan query
$stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");

// Cek error prepare
if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Gagal menyiapkan statement: " . $conn->error
    ]);
    exit;
}

// Bind parameter
$stmt->bind_param("sss", $name, $email, $message);

// Eksekusi query
if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "id" => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Gagal menyimpan data: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>