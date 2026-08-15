<?php
  
include "db.php";

header("Content-Type: application/json");

$customer = $_POST["customer"];
$product  = $_POST["product"];
$quantity = $_POST["quantity"];
$total    = $_POST["total"];

// Get current stock
$get = $conn->prepare("SELECT stock, price FROM products WHERE id=?");
$get->bind_param("i",$product);
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
$currentStock = $row["stock"];
$price = $row["price"];


if($quantity > $currentStock){

    echo json_encode([
        "success"=>false,
        "message"=>"Not enough stock."
    ]);
    exit;
}

// Save sale
$stmt = $conn->prepare("
INSERT INTO sales(customer_id,product_id,price,quantity,total)
VALUES(?,?,?,?,?)
");

$stmt->bind_param(
    "iidid",
    $customer,
    $product,
    $price,
    $quantity,
    $total
);

if($stmt->execute()){

    // Reduce stock
    $update = $conn->prepare("
    UPDATE products
    SET stock = stock - ?
    WHERE id=?
    ");

    $update->bind_param("ii",$quantity,$product);
    $update->execute();

    echo json_encode([
        "success"=>true,
        "message"=>"Sale saved successfully."
    ]);

}else{

    echo json_encode([
        "success"=>false,
        "message"=>"Could not save sale."
    ]);

}

$conn->close();

?>