<?php

include "auth.php";
include "db.php";

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
    echo "Please fill in all fields";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {

    $user = $result->fetch_assoc();

    if (password_verify($password, $user["password"])) {

        $_SESSION["user"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        set_user_cookie($user["id"]);

        echo "success";

    } else {

        echo "Wrong password";

    }

} else {

    echo "User not found";

}

$conn->close();
