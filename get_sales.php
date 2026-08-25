<?php

include "db.php";
include "auth.php";
$userId = require_login();

$stmt = $conn->prepare("
SELECT
sales.id,
customers.name AS customer,
products.name AS product,
sales.quantity,
sales.total,
sales.sale_date

FROM sales

JOIN customers
ON sales.customer_id = customers.id

JOIN products
ON sales.product_id = products.id

WHERE sales.user_id = ?

ORDER BY sales.id DESC
");

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header("Content-Type: application/json");
echo json_encode($data);

$stmt->close();
$conn->close();
?>
