<?php
// diplom_project\actions/action_course-getTest.php
session_start();
require_once("../database/dbconnect.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
    exit;
}

$userId = $_SESSION['userID'] ?? null;
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit;
}

// Получаем ID теста из формы
$lectureId = intval($_POST['lecture_id'] ?? 0);
if ($lectureId === 0) {
    echo json_encode(['success' => false, 'message' => 'Не указан ID лекции']);
    exit;
}

// Получаем ID теста из таблицы lecture
$stmt = $dbcon->prepare("SELECT test_id FROM lecture WHERE id = ?");
$stmt->bind_param("i", $lectureId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$testId = $row['test_id'];

if (!$testId) {
    echo json_encode(['success' => false, 'message' => 'Тест для этой лекции не найден']);
    exit;
}

// Проверяем, сколько попыток уже было
$stmtTry = $dbcon->prepare("SELECT try FROM completed_test WHERE id_student = ? AND id_test = ?");
$stmtTry->bind_param("ii", $userId, $testId);
$stmtTry->execute();
$stmtTry->store_result();

if ($stmtTry->num_rows > 0) {
    $stmtTry->bind_result($currentTry);
    $stmtTry->fetch();
} else {
    $currentTry = 0; // Нет записей — первая попытка
    $oldAssessment = null; // что-то новое
}

if ($currentTry >= 3) {
    echo json_encode([
        'success' => false,
        'message' => 'У вас не осталось попыток',
        'attemptsLeft' => 0
    ]);
    exit;
}

// Считываем ответы пользователя
$totalQuestions = 0;
$correctCount = 0;

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
        $correctAnswer = $row['correct_option'];
        if ($value === $correctAnswer) {
            $correctCount++;
        }
    }
}

$correctPercentage = ($totalQuestions > 0) ? ($correctCount / $totalQuestions) * 100 : 0;
$grade = 2;
if ($correctPercentage === 100) {
    $grade = 5;
} elseif ($correctPercentage >= 75) {
    $grade = 4;
} elseif ($correctPercentage >= 50) {
    $grade = 3;
}
// Если запись уже существует — обновляем, иначе добавляем новую
$date = date('Y-m-d'); // Получаем текущую дату в формате YYYY-MM-DD

if ($stmtTry->num_rows > 0) {
    $newTry = $currentTry + 1;

    $stmtUpdate = $dbcon->prepare("UPDATE completed_test SET assessment = ?, try = ?, date_completed = ? WHERE id_student = ? AND id_test = ?");
    $stmtUpdate->bind_param("iisii", $grade, $newTry, $date, $userId, $testId);
    $stmtUpdate->execute();
} else {
    $newTry = 1;

    $stmtInsert = $dbcon->prepare("INSERT INTO completed_test (id_student, id_test, assessment, try, date_completed) VALUES (?, ?, ?, ?, ?)");
    $stmtInsert->bind_param("iiiii", $userId, $testId, $grade, $newTry, $date);
    $stmtInsert->execute();
}

$attemptsLeft = 3 - $newTry;

echo json_encode([
    'success' => true,
    'message' => 'Тест успешно отправлен!',
    'attemptsLeft' => $attemptsLeft,
    'grade' => $grade,
    'correctPercentage' => $correctPercentage
]);
