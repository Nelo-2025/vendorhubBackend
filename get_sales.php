<?php

include "db.php";

$sql = "
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

ORDER BY sales.id DESC
";

$result = $conn->query($sql);

$data=[];

while($row=$result->fetch_assoc()){
    $data[]=$row;
}

header("Content-Type: application/json");

echo json_encode($data);

$conn->close();

?>