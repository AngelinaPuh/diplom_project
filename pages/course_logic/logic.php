<?php
// logic.php

// В начале файла
$startTime = microtime(true);

// Подключение функций кэширования
require_once 'cache_functions.php';

// Проверяем кэш для разделов
$sectionsCacheKey = 'sections_data';
$sections = getCache($sectionsCacheKey);

if (!$sections) {
    // Запрос для получения всех разделов
    $sectionsQuery = "SELECT id, title_section FROM section ORDER BY id ASC";
    $sectionsResult = $dbcon->query($sectionsQuery);

    if ($sectionsResult === false) {
        die("Ошибка при получении разделов: " . $dbcon->error);
    }

    $sections = $sectionsResult->fetch_all(MYSQLI_ASSOC);
    setCache($sectionsCacheKey, $sections);
}

// Проверяем кэш для лекций
$lecturesCacheKey = 'lectures_data';
$lectures = getCache($lecturesCacheKey);

if (!$lectures) {
    // Запрос для получения всех лекций
    $lecturesQuery = "SELECT id, id_section, title_lecture, order_lecture FROM lecture ORDER BY id_section ASC, order_lecture ASC";
    $lecturesResult = $dbcon->query($lecturesQuery);

    if ($lecturesResult === false) {
        die("Ошибка при получении лекций: " . $dbcon->error);
    }

    $lectures = $lecturesResult->fetch_all(MYSQLI_ASSOC);
    setCache($lecturesCacheKey, $lectures);
}

// Группируем лекции по разделам
$lecturesBySection = [];
foreach ($lectures as $lecture) {
    $lecturesBySection[$lecture['id_section']][] = $lecture;
}

// Создаем общий массив всех лекций
$allLectures = [];
foreach ($lecturesBySection as $sectionLectures) {
    foreach ($sectionLectures as $lecture) {
        $allLectures[] = $lecture;
    }
}

// Сортируем все лекции по order_lecture
usort($allLectures, function ($a, $b) {
    return $a['order_lecture'] - $b['order_lecture'];
});

// Проверяем авторизацию пользователя
$isAuthorized = isset($_SESSION['authorization_dostup']) && $_SESSION['authorization_dostup'] === true;

// В конце файла
$endTime = microtime(true);