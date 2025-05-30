<?php
session_start();
require_once("../database/dbconnect.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
    exit;
}

$userId = $_SESSION['userID'] ?? null;
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit;
}

$lectureId = intval($_GET['lecture_id'] ?? 0);
if ($lectureId === 0) {
    echo json_encode(['success' => false, 'message' => 'Не указан ID лекции']);
    exit;
}

// Получаем test_id из таблицы lecture
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
    $currentTry = 0;
}

$attemptsLeft = 3 - $currentTry;

echo json_encode([
    'success' => true,
    'attemptsLeft' => $attemptsLeft
]);
?>