<?php

include "auth.php";

$_SESSION = [];

if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );

}

clear_user_cookie();
session_destroy();

header("Content-Type: application/json");

echo json_encode([
    "success" => true,
    "message" => "logout"
]);
