<?php

include "db.php";

$sql = "SELECT id, name, price, stock FROM products ORDER BY name";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

header("Content-Type: application/json");
echo json_encode($data);

$conn->close();

?>