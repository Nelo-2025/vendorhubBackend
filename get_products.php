<?php

include "db.php";
include "auth.php";
$userId = require_login();

$stmt = $conn->prepare("SELECT * FROM products WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

header("Content-Type: application/json");
echo json_encode($products);

$stmt->close();
$conn->close();
?>
