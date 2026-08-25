<?php

function product_image_dir() {
    $dir = __DIR__ . "/ppdct";
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    return $dir;
}

function upload_product_image($file) {

    if (!isset($file) || !isset($file["error"]) || $file["error"] === UPLOAD_ERR_NO_FILE) {
        return [
            "ok" => true,
            "filename" => null,
            "message" => ""
        ];
    }

    if ($file["error"] !== UPLOAD_ERR_OK) {
        return [
            "ok" => false,
            "filename" => null,
            "message" => "Image upload failed."
        ];
    }

    $allowed = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp",
        "image/gif" => "gif"
    ];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file["tmp_name"]);
    finfo_close($finfo);

    if (!isset($allowed[$mime])) {
        return [
            "ok" => false,
            "filename" => null,
            "message" => "Only JPG, PNG, WEBP, or GIF images are allowed."
        ];
    }

    if ($file["size"] > 5 * 1024 * 1024) {
        return [
            "ok" => false,
            "filename" => null,
            "message" => "Image must be 5MB or smaller."
        ];
    }

    $dir = product_image_dir();
    $filename = "p_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $allowed[$mime];
    $dest = $dir . "/" . $filename;

    if (!move_uploaded_file($file["tmp_name"], $dest)) {
        return [
            "ok" => false,
            "filename" => null,
            "message" => "Could not save image."
        ];
    }

    return [
        "ok" => true,
        "filename" => $filename,
        "message" => ""
    ];
}

function delete_product_image_file($filename) {
    if (!$filename) {
        return;
    }

    $path = product_image_dir() . "/" . basename($filename);

    if (is_file($path)) {
        @unlink($path);
    }
}
?>
