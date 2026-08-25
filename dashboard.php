<?php

include "db.php";
include "auth.php";
$userId = require_login();

header("Content-Type: application/json");

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM products WHERE user_id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM customers WHERE user_id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("SELECT SUM(total) AS total FROM sales WHERE user_id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$sales = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM products WHERE user_id=? AND stock <= 5");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stock = $stmt->get_result()->fetch_assoc();
$stmt->close();

echo json_encode([
    "products"  => (int)$product["total"],
    "customers" => (int)$customer["total"],
    "sales"     => $sales["total"] ? (float)$sales["total"] : 0,
    "lowstock"  => (int)$stock["total"]
]);

$conn->close();
?>
