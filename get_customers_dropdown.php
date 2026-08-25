<?php

include "db.php";
include "auth.php";
$userId = require_login();

$stmt = $conn->prepare("SELECT id, name FROM customers WHERE user_id=? ORDER BY name");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header("Content-Type: application/json");
echo json_encode($data);

$stmt->close();
$conn->close();
?>
