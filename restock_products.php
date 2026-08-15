<?php

include "db.php";

$id = $_POST["id"];
$quantity = $_POST["quantity"];

$stmt = $conn->prepare("
    UPDATE products
    SET stock = stock + ?
    WHERE id = ?
");

$stmt->bind_param("ii", $quantity, $id);

header("Content-Type: application/json");

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Stock Updated Successfully"
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Update Failed"
    ]);

}

$stmt->close();
$conn->close();

?>