<?php

include "db.php";
include "auth.php";
$userId = require_login();

$id = $_POST["id"];
$name = $_POST["name"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$address = $_POST["address"];

$stmt = $conn->prepare(
    "UPDATE customers SET name=?, email=?, phone=?, address=? WHERE id=? AND user_id=?"
);
$stmt->bind_param("ssssii", $name, $email, $phone, $address, $id, $userId);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$stmt->close();
$conn->close();
?>
