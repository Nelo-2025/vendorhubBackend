<?php

include "db.php";
include "auth.php";
$userId = require_login();

$name = $_POST["name"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$address = $_POST["address"];

$stmt = $conn->prepare(
    "INSERT INTO customers(name,phone,email,address,user_id)
     VALUES(?,?,?,?,?)"
);

$stmt->bind_param(
    "ssssi",
    $name,
    $phone,
    $email,
    $address,
    $userId
);

if ($stmt->execute()) {
    echo "Customer added successfully.";
} else {
    echo "Failed to add customer.";
}

$stmt->close();
$conn->close();
?>
