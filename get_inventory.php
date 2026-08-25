<?php

include "db.php";
include "auth.php";
$userId = require_login();

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
WHERE user_id = ?
AND name LIKE CONCAT('%', ?, '%')
ORDER BY name ASC
");

$stmt->bind_param("is", $userId, $search);
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
