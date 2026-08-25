<?php

include "db.php";
include "auth.php";
$userId = require_login();

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM sales WHERE user_id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

header("Content-Type: application/json");
echo json_encode($row);

$stmt->close();
$conn->close();
?>
