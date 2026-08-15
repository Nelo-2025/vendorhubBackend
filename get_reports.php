<?php

include "db.php";

$response = [];

/* =========================
   TOTAL SALES
========================= */

$sql = "SELECT COUNT(*) AS totalSales FROM sales";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$response["totalSales"] = $row["totalSales"];


/* =========================
   TOTAL REVENUE
========================= */

$sql = "SELECT IFNULL(SUM(total),0) AS totalRevenue FROM sales";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$response["totalRevenue"] = $row["totalRevenue"];


/* =========================
   PRODUCTS SOLD
========================= */

$sql = "SELECT IFNULL(SUM(quantity),0) AS productsSold FROM sales";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$response["productsSold"] = $row["productsSold"];


/* =========================
   RECENT SALES
========================= */

$sql = "
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
ORDER BY sales.id DESC
LIMIT 10
";

$result = $conn->query($sql);

$sales = [];

while ($row = $result->fetch_assoc()) {
    $sales[] = $row;
}

$response["sales"] = $sales;


/* =========================
   RETURN JSON
========================= */

header("Content-Type: application/json");

echo json_encode($response);

$conn->close();

?>