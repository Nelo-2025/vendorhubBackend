<?php

include "db.php";

$search = "";

if (isset($_GET["search"])) {
    $search = $_GET["search"];
}

$stmt = $conn->prepare("
SELECT
    id,
    name,
    category,
    price,
    stock
FROM products
WHERE name LIKE CONCAT('%', ?, '%')
ORDER BY name ASC
");

$stmt->bind_param("s", $search);
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