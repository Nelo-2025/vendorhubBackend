<?php

include "db.php";
include "auth.php";
$userId = require_login();

header("Content-Type: application/json");

$stmt = $conn->prepare("SELECT id, name, email FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);
    exit;
}

$user = $result->fetch_assoc();

echo json_encode([
    "success" => true,
    "id" => (int)$user["id"],
    "name" => $user["name"],
    "email" => $user["email"]
]);

$stmt->close();
$conn->close();
?>
