<?php

include "db.php";
include "auth.php";
include "product_image.php";
$userId = require_login();

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

$upload = upload_product_image($_FILES["image"] ?? null);

if (!$upload["ok"]) {
    echo json_encode([
        "success" => false,
        "message" => $upload["message"]
    ]);
    exit();
}

$image = $upload["filename"];

if ($image) {
    $stmt = $conn->prepare(
        "INSERT INTO products(name, category, price, stock, user_id, image) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssdiis", $name, $category, $price, $stock, $userId, $image);
} else {
    $stmt = $conn->prepare(
        "INSERT INTO products(name, category, price, stock, user_id) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssdii", $name, $category, $price, $stock, $userId);
}

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Product added successfully."
    ]);
} else {
    if ($image) {
        delete_product_image_file($image);
    }
    echo json_encode([
        "success" => false,
        "message" => "Failed to add product. If this persists, add an `image` column to products."
    ]);
}

$stmt->close();
$conn->close();
?>
