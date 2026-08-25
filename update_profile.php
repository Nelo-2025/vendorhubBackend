<?php

include "db.php";
include "auth.php";
$userId = require_login();

header("Content-Type: application/json");

$name = trim($_POST["name"] ?? "");

if ($name === "") {
    echo json_encode([
        "success" => false,
        "message" => "Name is required."
    ]);
    exit;
}

$stmt = $conn->prepare("UPDATE users SET name=? WHERE id=?");
$stmt->bind_param("si", $name, $userId);

if ($stmt->execute()) {
    $_SESSION["user_name"] = $name;

    echo json_encode([
        "success" => true,
        "message" => "Name updated successfully.",
        "name" => $name
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Could not update name."
    ]);
}

$stmt->close();
$conn->close();
?>
