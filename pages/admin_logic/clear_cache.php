<?php
// ../pages/admin_logic/clear_cache.php

$response = ["success" => false, "message" => ""];

$cacheDir = __DIR__ . '/../course_logic/cache/';

if (is_dir($cacheDir)) {
    $files = glob($cacheDir . '*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    $response["success"] = true;
    $response["message"] = "Кэш очищен";
} else {
    $response["message"] = "Директория кэша не найдена";
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);