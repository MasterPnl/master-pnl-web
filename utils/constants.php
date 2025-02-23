<?php
require_once 'lib/MysqliDb.php';
const API_URL = 'http://localhost:3000';
const IMAGE_SECRET_KEY = "master-web-key.*/?";
const DB_CONFIG = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'db' => 'master_pnl_db',
    'port' => 3306,
    'charset' => 'utf8'
];

$db = new MysqliDb(DB_CONFIG);

function encryptPath($path) {
    $signature = hash_hmac('sha256', $path, IMAGE_SECRET_KEY);
    return urlencode(base64_encode($path . "::" . $signature));
}
function decryptPath($encryptedPath) {
    $decoded = base64_decode(urldecode($encryptedPath));
    if (!$decoded) {
        return false;
    }

    // URL ve imzayı ayır
    list($originalUrl, $signature) = explode("::", $decoded, 2);

    // İmza doğrulaması yap
    $expectedSignature = hash_hmac('sha256', $originalUrl, IMAGE_SECRET_KEY);
    if (hash_equals($expectedSignature, $signature)) {
        return $originalUrl;
    }

    return false;
}