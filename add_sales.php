<?php

include "db.php";
include "auth.php";
$userId = require_login();

header("Content-Type: application/json");

$customer = $_POST["customer"];
$product  = $_POST["product"];
$quantity = $_POST["quantity"];
$total    = $_POST["total"];

// Product must belong to this user
$get = $conn->prepare("SELECT stock, price FROM products WHERE id=? AND user_id=?");
$get->bind_param("ii", $product, $userId);
$get->execute();

$result = $get->get_result();

if ($result->num_rows == 0) {
    echo json_encode([
        "success" => false,
        "message" => "Product not found."
    ]);
    exit;
}

$row = $result->fetch_assoc();
$currentStock = $row["stock"];
$price = $row["price"];

if ($quantity > $currentStock) {
    echo json_encode([
        "success" => false,
        "message" => "Not enough stock."
    ]);
    exit;
}

// Customer must belong to this user
$cust = $conn->prepare("SELECT id FROM customers WHERE id=? AND user_id=?");
$cust->bind_param("ii", $customer, $userId);
$cust->execute();

if ($cust->get_result()->num_rows == 0) {
    echo json_encode([
        "success" => false,
        "message" => "Customer not found."
    ]);
    exit;
}

$stmt = $conn->prepare("
INSERT INTO sales(customer_id,product_id,price,quantity,total,user_id)
VALUES(?,?,?,?,?,?)
");

$stmt->bind_param(
    "iididi",
    $customer,
    $product,
    $price,
    $quantity,
    $total,
    $userId
);

if ($stmt->execute()) {

    $update = $conn->prepare("
    UPDATE products
    SET stock = stock - ?
    WHERE id=? AND user_id=?
    ");

    $update->bind_param("iii", $quantity, $product, $userId);
    $update->execute();

    echo json_encode([
        "success" => true,
        "message" => "Sale saved successfully."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Could not save sale."
    ]);

}

$conn->close();
?>
