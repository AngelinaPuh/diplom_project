<?php
// pages/course_logic/logic.php

// В начале файла
$startTime = microtime(true);

// Подключение функций кэширования
require_once 'cache_functions.php';
require_once("database/dbconnect.php");

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

// работаем с тестами!!!!!!!

// Функция для получения тестов и вопросов
function getTestQuestions($lectureId, $dbcon)
{
    // Получаем id_test для лекции
    $testQuery = "SELECT id_test FROM lecture WHERE id = ?";
    $stmt = $dbcon->prepare($testQuery);
    $stmt->bind_param("i", $lectureId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null; // Нет теста для этой лекции
    }

    $testData = $result->fetch_assoc();
    $testId = $testData['id_test'];

    // Получаем вопросы для теста
    $questionsQuery = "
        SELECT id, title_questions, correct_option, wrong_answer1, wrong_answer2, wrong_answer3 
        FROM questions 
        WHERE id_test = ?
    ";
    $stmt = $dbcon->prepare($questionsQuery);
    $stmt->bind_param("i", $testId);
    $stmt->execute();
    $questionsResult = $stmt->get_result();

    $questions = [];
    while ($row = $questionsResult->fetch_assoc()) {
        // Собираем варианты ответов в случайном порядке
        $options = [
            $row['correct_option'],
            $row['wrong_answer1'],
            $row['wrong_answer2'],
            $row['wrong_answer3']
        ];
        shuffle($options); // Перемешиваем варианты ответов

        $questions[] = [
            'id' => $row['id'],
            'title' => $row['title_questions'],
            'options' => $options,
        ];
    }

    return $questions;
}

// Добавляем тесты к каждой лекции
foreach ($allLectures as &$lecture) {
    $lecture['test_questions'] = getTestQuestions($lecture['id'], $dbcon);
}// Проверяем авторизацию пользователя
$isAuthorized = isset($_SESSION['authorization_dostup']) && $_SESSION['authorization_dostup'] === true;

// В конце файла
$endTime = microtime(true);