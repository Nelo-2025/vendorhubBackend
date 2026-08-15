<?php
include "db.php";

header("Content-Type: application/json");

if (!isset($_POST["id"])) {
    echo json_encode([
        "success" => false,
        "message" => "No product ID provided."
    ]);
    exit;
}

$id = intval($_POST["id"]);

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
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