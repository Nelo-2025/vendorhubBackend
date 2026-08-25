<?php

include "db.php";
include "auth.php";
$userId = require_login();

$id = $_POST["id"];
$quantity = $_POST["quantity"];

$stmt = $conn->prepare("
    UPDATE products
    SET stock = stock + ?
    WHERE id = ? AND user_id = ?
");

$stmt->bind_param("iii", $quantity, $id, $userId);

header("Content-Type: application/json");

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Stock Updated Successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Update Failed"
    ]);
}

$stmt->close();
$conn->close();
?>
