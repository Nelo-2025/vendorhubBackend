<?php

include "auth.php";
include "db.php";

header("Content-Type: application/json");

$userId = current_user_id();

if ($userId <= 0) {
    echo json_encode([
        "logged_in" => false
    ]);
    exit;
}

$name = $_SESSION["user_name"] ?? "";
$email = "";

$stmt = $conn->prepare("SELECT name, email FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $name = $user["name"];
    $email = $user["email"];
    $_SESSION["user_name"] = $name;
}

$stmt->close();
$conn->close();

echo json_encode([
    "logged_in" => true,
    "user_id" => $userId,
    "name" => $name,
    "email" => $email
]);
?>
