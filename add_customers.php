<?php

include "db.php";

$name = $_POST["name"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$address = $_POST["address"];

$stmt = $conn->prepare(
"INSERT INTO customers(name,phone,email,address)
VALUES(?,?,?,?)"
);

$stmt->bind_param(
"ssss",
$name,
$phone,
$email,
$address
);

if($stmt->execute()){

    echo "Customer added successfully.";

}else{

    echo "Failed to add customer.";

}

$stmt->close();
$conn->close();

?>