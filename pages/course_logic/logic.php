<?php
// pages/course_logic/logic.php

// В начале файла
$startTime = microtime(true);

// Подключение функций кэширования
require_once 'cache_functions.php';
require_once("database/dbconnect.php");

// Флаг для отслеживания загрузки из кэша
$isLoadedFromCache = true;

// Проверяем кэш для разделов
$sectionsCacheKey = 'sections_data';
$sections = getCache($sectionsCacheKey);

if (!$sections) {
    $isLoadedFromCache = false;
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
    $isLoadedFromCache = false;
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

if (empty($lecturesBySection)) {
    die("Нет доступных лекций.");
}

// Сортируем все лекции по order_lecture
usort($allLectures, function ($a, $b) {
    return $a['order_lecture'] - $b['order_lecture'];
});

// Проверяем авторизацию пользователя
$isAuthorized = isset($_SESSION['authorization_dostup']) && $_SESSION['authorization_dostup'] === true;

// В конце файла
$endTime = microtime(true);

// работаем с тестами!!!!!!!

// Функция для получения тестов и вопросов, но снчала проверяется есть ли кэш этих тестов
function getTestQuestions($lectureId, $dbcon)
{
    $cacheKey = 'test_questions_lecture_' . $lectureId;

    // Проверяем кэш
    if ($cachedData = getCache($cacheKey)) {
        return $cachedData;
    }

    // Получаем test_id  для лекции
    $testQuery = "SELECT test_id  FROM lecture WHERE id = ?";
    $stmt = $dbcon->prepare($testQuery);
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $dbcon->error);
    }
    $stmt->bind_param("i", $lectureId);
    if (!$stmt->execute()) {
        die("Ошибка выполнения запроса: " . $stmt->error);
    }
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null; // Нет теста для этой лекции
    }

    $testData = $result->fetch_assoc();
    $testId = $testData['test_id'];

    // Получаем вопросы для теста
    $questionsQuery = "
        SELECT id, title_questions, correct_option, wrong_answer1, wrong_answer2, wrong_answer3 
        FROM questions 
        WHERE id_test = ?
    ";
    $stmt = $dbcon->prepare($questionsQuery);
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $dbcon->error);
    }
    $stmt->bind_param("i", $testId);
    if (!$stmt->execute()) {
        die("Ошибка выполнения запроса: " . $stmt->error);
    }
    $questionsResult = $stmt->get_result();

    $questions = [];
    while ($row = $questionsResult->fetch_assoc()) {
        $options = [
            $row['correct_option'],
            $row['wrong_answer1'],
            $row['wrong_answer2'],
            $row['wrong_answer3']
        ];
        shuffle($options);

        $questions[] = [
            'id' => $row['id'],
            'title' => $row['title_questions'],
            'options' => $options,
        ];
    }

    // Сохраняем тесты в кэш
    setCache($cacheKey, $questions);

    return $questions;
}
// Добавляем тесты к каждой лекции
foreach ($allLectures as &$lecture) {
    $lecture['test_questions'] = getTestQuestions($lecture['id'], $dbcon);
}

if (empty($allLectures)) {
    die("Ошибка при создании массива всех лекций.");
}

// Проверяем авторизацию пользователя
$isAuthorized = isset($_SESSION['authorization_dostup']) && $_SESSION['authorization_dostup'] === true;

// В конце файла
$endTime = microtime(true);
