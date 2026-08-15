<?php

include "db.php";

header("Content-Type: application/json");

$name = trim($_POST["name"] ?? "");
$category = trim($_POST["category"] ?? "");
$price = $_POST["price"] ?? "";
$stock = $_POST["stock"] ?? "";

if ($name == "" || $category == "" || $price == "" || $stock == "") {
    echo json_encode([
        "success" => false,
        "message" => "All fields are required."
    ]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO products(name, category, price, stock) VALUES (?, ?, ?, ?)");

$stmt->bind_param("ssdi", $name, $category, $price, $stock);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Product added successfully."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Failed to add product."
    ]);

}

$stmt->close();
$conn->close();

?>