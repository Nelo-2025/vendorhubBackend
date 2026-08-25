<?php

include "db.php";
include "auth.php";
$userId = require_login();

$stmt = $conn->prepare("SELECT * FROM products WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>
