<?php
include "db.php";

$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

header("Content-Type: application/json");
echo json_encode($products);
?>