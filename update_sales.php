<?php

include "db.php";

header("Content-Type: application/json");

$id       = $_POST["id"];
$customer = $_POST["customer_id"];
$product  = $_POST["product_id"];
$quantity = $_POST["quantity"];

// Get current product price
$get = $conn->prepare("
SELECT price
FROM products
WHERE id=?
");

$get->bind_param("i", $product);
$get->execute();

$result = $get->get_result();

if($result->num_rows == 0){

    echo json_encode([
        "success"=>false,
        "message"=>"Product not found."
    ]);
    exit;

}

$row = $result->fetch_assoc();

$price = $row["price"];
$total = $price * $quantity;

$stmt = $conn->prepare("
UPDATE sales
SET
customer_id=?,
product_id=?,
quantity=?,
price=?,
total=?
WHERE id=?
");

$stmt->bind_param(
    "iiiddi",
    $customer,
    $product,
    $quantity,
    $price,
    $total,
    $id
);

if($stmt->execute()){

    echo json_encode([
        "success"=>true,
        "message"=>"Sale updated successfully."
    ]);

}else{

    echo json_encode([
        "success"=>false,
        "message"=>"Could not update sale."
    ]);

}

$conn->close();

?>