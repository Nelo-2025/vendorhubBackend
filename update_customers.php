<?php

include "db.php";

$id = $_POST["id"];
$name = $_POST["name"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$address = $_POST["address"];

$stmt = $conn->prepare("UPDATE customers SET name=?, email=?, phone=?, address=? WHERE id=?");
$stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);

if($stmt->execute()){
    echo "success";
}else{
    echo "error";
}

$stmt->close();
$conn->close();

?>