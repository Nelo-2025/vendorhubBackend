<?php

if (session_status() === PHP_SESSION_NONE) {

    session_set_cookie_params([
        "lifetime" => 0,
        "path" => "/",
        "httponly" => true,
        "samesite" => "Lax"
    ]);

    session_start();

}

define("VENDORHUB_USER_COOKIE", "vendorhub_user_id");

function set_user_cookie($userId) {

    setcookie(
        VENDORHUB_USER_COOKIE,
        (string)(int)$userId,
        [
            "expires" => 0,
            "path" => "/",
            "httponly" => true,
            "samesite" => "Lax"
        ]
    );

    $_COOKIE[VENDORHUB_USER_COOKIE] = (string)(int)$userId;

}

function clear_user_cookie() {

    setcookie(
        VENDORHUB_USER_COOKIE,
        "",
        [
            "expires" => time() - 42000,
            "path" => "/",
            "httponly" => true,
            "samesite" => "Lax"
        ]
    );

    unset($_COOKIE[VENDORHUB_USER_COOKIE]);

}

function current_user_id() {

    if (!empty($_COOKIE[VENDORHUB_USER_COOKIE])) {
        return (int)$_COOKIE[VENDORHUB_USER_COOKIE];
    }

    if (!empty($_SESSION["user"])) {
        return (int)$_SESSION["user"];
    }

    return 0;

}

function require_login() {

    $userId = current_user_id();

    if ($userId <= 0) {

        http_response_code(401);
        header("Content-Type: application/json");
        echo json_encode([
            "success" => false,
            "message" => "Unauthorized"
        ]);
        exit;

    }

    // Keep session in sync with cookie
    $_SESSION["user"] = $userId;

    return $userId;

}
