<?php

include "db.php";

header("Content-Type: application/json");

$id = $_GET["id"];

$stmt = $conn->prepare("DELETE FROM sales WHERE id=?");
$stmt->bind_param("i", $id);

if($stmt->execute()){

    echo json_encode([
        "success" => true,
        "message" => "Sale deleted successfully."
    ]);

}else{

    echo json_encode([
        "success" => false,
        "message" => "Could not delete sale."
    ]);

}

$conn->close();

?>