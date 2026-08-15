<?php

include "db.php";

$id = $_GET["id"];

$stmt = $conn->prepare(
"DELETE FROM customers WHERE id=?"
);

$stmt->bind_param("i",$id);

if($stmt->execute()){

    echo "Customer deleted.";

}else{

    echo "Delete failed.";

}

$stmt->close();
$conn->close();

?>