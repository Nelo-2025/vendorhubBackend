<?php

include "db.php";

$result = $conn->query("SELECT COUNT(*) AS total FROM sales");

$row = $result->fetch_assoc();

header("Content-Type: application/json");

echo json_encode($row);

$conn->close();

?>