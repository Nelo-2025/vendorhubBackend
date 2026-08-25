<?php

include "db.php";
include "auth.php";
include "product_image.php";
$userId = require_login();

header("Content-Type: application/json");

if (!isset($_POST["id"])) {
    echo json_encode([
        "success" => false,
        "message" => "No product ID provided."
    ]);
    exit;
}

$id = intval($_POST["id"]);

$find = $conn->prepare("SELECT image FROM products WHERE id = ? AND user_id = ?");
$find->bind_param("ii", $id, $userId);
$find->execute();
$row = $find->get_result()->fetch_assoc();
$find->close();

$stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $userId);

if ($stmt->execute()) {
    if ($row && !empty($row["image"])) {
        delete_product_image_file($row["image"]);
    }
    echo json_encode([
        "success" => true,
        "message" => "Product deleted successfully."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to delete product."
    ]);
}

$stmt->close();
$conn->close();
?>
