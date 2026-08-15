<?php

include "db.php";

header("Content-Type: application/json");

$id = $_POST["id"];
$name = $_POST["name"];
$category = $_POST["category"];
$price = $_POST["price"];
$stock = $_POST["stock"];

$stmt = $conn->prepare(
    "UPDATE products
     SET name=?, category=?, price=?, stock=?
     WHERE id=?"
);

$stmt->bind_param(
    "ssdii",
    $name,
    $category,
    $price,
    $stock,
    $id
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Product updated successfully."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Update failed."
    ]);

}

$stmt->close();
$conn->close();

?>