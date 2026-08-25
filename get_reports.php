<?php

include "db.php";
include "auth.php";
$userId = require_login();

$response = [];

$stmt = $conn->prepare("SELECT COUNT(*) AS totalSales FROM sales WHERE user_id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$response["totalSales"] = $row["totalSales"];
$stmt->close();

$stmt = $conn->prepare("SELECT IFNULL(SUM(total),0) AS totalRevenue FROM sales WHERE user_id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$response["totalRevenue"] = $row["totalRevenue"];
$stmt->close();

$stmt = $conn->prepare("SELECT IFNULL(SUM(quantity),0) AS productsSold FROM sales WHERE user_id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$response["productsSold"] = $row["productsSold"];
$stmt->close();

$stmt = $conn->prepare("
SELECT
    sales.id,
    customers.name AS customer,
    products.name AS product,
    sales.quantity,
    sales.total AS total,
    sales.sale_date
FROM sales
INNER JOIN customers
ON sales.customer_id = customers.id
INNER JOIN products
ON sales.product_id = products.id
WHERE sales.user_id = ?
ORDER BY sales.id DESC
LIMIT 10
");

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$sales = [];

while ($row = $result->fetch_assoc()) {
    $sales[] = $row;
}

$response["sales"] = $sales;
$stmt->close();

header("Content-Type: application/json");
echo json_encode($response);

$conn->close();
?>
