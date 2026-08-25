<?php

include "db.php";
include "auth.php";
$userId = require_login();

$id = $_GET["id"];

$stmt = $conn->prepare("DELETE FROM customers WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $userId);

if ($stmt->execute()) {
    echo "Customer deleted.";
} else {
    echo "Delete failed.";
}

$stmt->close();
$conn->close();
?>
