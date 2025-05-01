<?php
// cache_functions.php

// Функция для получения данных из кэша
function getCache($key)
{
    $cacheFile = __DIR__ . '/cache/' . md5($key) . '.cache';
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 3600) {
        return json_decode(file_get_contents($cacheFile), true);
    }
    return false;
}

// Функция для сохранения данных в кэш
function setCache($key, $data, $ttl = 3600)
{
    $cacheDir = __DIR__ . '/cache/';
    if (!is_dir($cacheDir)) {
        if (!mkdir($cacheDir, 0777, true)) {
            die("Не удалось создать папку кэша: $cacheDir");
        }
    }

    $cacheFile = $cacheDir . md5($key) . '.cache';
    if (file_put_contents($cacheFile, json_encode($data)) === false) {
        die("Не удалось записать файл кэша: $cacheFile");
    }
}

// Функция для очистки кэша
function clearCache($key)
{
    $cacheFile = __DIR__ . '/cache/' . md5($key) . '.cache';
    if (file_exists($cacheFile)) {
        unlink($cacheFile);
    }
}