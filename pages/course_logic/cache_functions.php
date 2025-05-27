<?php
// cache_functions.php

// Функция для получения данных из кэша
function getCache($key)
{
    $cacheFile = __DIR__ . '/cache/' . md5($key) . '.cache';
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 3600) {
        $data = file_get_contents($cacheFile);
        if ($data === false) {
            error_log("Cache read error for key: $key");
            return false;
        }
        $decodedData = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error for key: $key - " . json_last_error_msg());
            return false;
        }
        return $decodedData;
    }
    return false;
}

// Функция для сохранения данных в кэш
function setCache($key, $data, $ttl = 3600)
{
    $cacheDir = __DIR__ . '/cache/';
    if (!is_dir($cacheDir)) {
        if (!mkdir($cacheDir, 0777, true)) {
            error_log("Failed to create cache directory: $cacheDir");
            return;
        }
    }
    $cacheFile = $cacheDir . md5($key) . '.cache';
    $jsonData = json_encode($data);
    if ($jsonData === false) {
        error_log("JSON encode error for key: $key - " . json_last_error_msg());
        return;
    }
    if (file_put_contents($cacheFile, $jsonData) === false) {
        error_log("Failed to write cache file: $cacheFile");
    } else {
        error_log("Cache saved successfully for key: $key");
    }
}

// Функция для очистки кэша
function clearCache($key)
{
    $cacheFile = __DIR__ . '/cache/' . md5($key) . '.cache';
    if (file_exists($cacheFile)) {
        if (!unlink($cacheFile)) {
            die("Не удалось удалить файл кэша: $cacheFile");
        }
    }
}

// уточнить потом при создании админки и удаления кэша
// Очистка кэша тестов для конкретной лекции
function clearTestCache($lectureId)
{
    $cacheKey = 'test_questions_lecture_' . $lectureId;
    clearCache($cacheKey);
}

// Очистка кэша всех лекций
function clearLecturesCache()
{
    $cacheKey = 'lectures_data';
    clearCache($cacheKey);
}

// Очистка кэша всех разделов
function clearSectionsCache()
{
    $cacheKey = 'sections_data';
    clearCache($cacheKey);
}
