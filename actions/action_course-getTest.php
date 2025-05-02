<?php
// actions/action_course-getTest.php
session_start();
require_once("../database/dbconnect.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
    exit;
}

$correctAnswers = [];
$selectedAnswers = [];
$totalQuestions = 0;
$correctCount = 0;

// Фильтруем только вопросы
foreach ($_POST as $key => $value) {
    if (strpos($key, 'question_') === 0) {
        $totalQuestions++;
        $questionId = substr($key, strlen('question_'));
        $query = "SELECT correct_option FROM questions WHERE id = ?";
        $stmt = $dbcon->prepare($query);
        $stmt->bind_param("i", $questionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $correctAnswers[$questionId] = $row['correct_option'];
        $selectedAnswers[$questionId] = $value;

        if ($value === $row['correct_option']) {
            $correctCount++;
        }
    }
}

// Вычисляем процент правильных ответов
$correctPercentage = ($totalQuestions > 0) ? ($correctCount / $totalQuestions) * 100 : 0;

// Проверяем, все ли ответы верны
$isAllCorrect = ($correctCount === $totalQuestions);

echo json_encode([
    'success' => $isAllCorrect,
    'correctPercentage' => $correctPercentage,
    'message' => $isAllCorrect ? 'Все ответы верны!' : 'Некоторые ответы неверны.'
]);