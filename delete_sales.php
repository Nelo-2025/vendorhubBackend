<?php

include "db.php";
include "auth.php";
$userId = require_login();

header("Content-Type: application/json");

$id = $_GET["id"];

$stmt = $conn->prepare("DELETE FROM sales WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $userId);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Sale deleted successfully."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Could not delete sale."
    ]);
}

$stmt->close();
$conn->close();
?>
