<?php

include "db.php";
include "auth.php";
$userId = require_login();

$stmt = $conn->prepare("SELECT * FROM customers WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$customers = [];

while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

echo json_encode($customers);

$stmt->close();
$conn->close();
?>
