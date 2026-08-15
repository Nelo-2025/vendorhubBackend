<?php

include "db.php";

header("Content-Type: application/json");

// Total Products
$product = $conn->query("
SELECT COUNT(*) AS total
FROM products
")->fetch_assoc();

// Total Customers
$customer = $conn->query("
SELECT COUNT(*) AS total
FROM customers
")->fetch_assoc();

// Total Sales Revenue
$sales = $conn->query("
SELECT SUM(total) AS total
FROM sales
")->fetch_assoc();

// Low Stock Products
$stock = $conn->query("
SELECT COUNT(*) AS total
FROM products
WHERE stock <= 5
")->fetch_assoc();

echo json_encode([

    "products"  => (int)$product["total"],
    "customers" => (int)$customer["total"],
    "sales"     => $sales["total"] ? (float)$sales["total"] : 0,
    "lowstock"  => (int)$stock["total"]

]);

$conn->close();

?>