<?php

include "db.php";
include "auth.php";
include "product_image.php";
$userId = require_login();

header("Content-Type: application/json");

$id = $_POST["id"] ?? "";
$name = $_POST["name"] ?? "";
$category = $_POST["category"] ?? "";
$price = $_POST["price"] ?? "";
$stock = $_POST["stock"] ?? "";

if ($id === "" || $name === "" || $category === "" || $price === "" || $stock === "") {
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

$newImage = $upload["filename"];
$oldImage = null;

if ($newImage) {
    $find = $conn->prepare("SELECT image FROM products WHERE id=? AND user_id=?");
    $find->bind_param("ii", $id, $userId);
    $find->execute();
    $row = $find->get_result()->fetch_assoc();
    $oldImage = $row["image"] ?? null;
    $find->close();

    $stmt = $conn->prepare(
        "UPDATE products
         SET name=?, category=?, price=?, stock=?, image=?
         WHERE id=? AND user_id=?"
    );
    $stmt->bind_param("ssdisii", $name, $category, $price, $stock, $newImage, $id, $userId);
} else {
    $stmt = $conn->prepare(
        "UPDATE products
         SET name=?, category=?, price=?, stock=?
         WHERE id=? AND user_id=?"
    );
    $stmt->bind_param("ssdiii", $name, $category, $price, $stock, $id, $userId);
}

if ($stmt->execute()) {
    if ($newImage && $oldImage) {
        delete_product_image_file($oldImage);
    }
    echo json_encode([
        "success" => true,
        "message" => "Product updated successfully."
    ]);
} else {
    if ($newImage) {
        delete_product_image_file($newImage);
    }
    echo json_encode([
        "success" => false,
        "message" => "Update failed."
    ]);
}

$stmt->close();
$conn->close();
?>
