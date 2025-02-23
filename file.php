<?php
require_once 'utils/constants.php';
if (isset($_GET['img'])) {
    $imageUrl = decryptPath($_GET['img']);

    if ($imageUrl && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
        header("Content-Type: image/jpeg");
        readfile($imageUrl);
        exit;
    } else {
        http_response_code(404);
        echo "Geçersiz veya hatalı URL.";
    }
}