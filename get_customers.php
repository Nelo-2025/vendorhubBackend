<?php

include "db.php";

$sql = "SELECT * FROM customers ORDER BY id DESC";

$result = $conn->query($sql);

$customers = [];

while($row = $result->fetch_assoc()){

    $customers[] = $row;

}

echo json_encode($customers);

$conn->close();

?>