<?php
// diplom_project/pages/profile_logic.php
$pageTitle = "Личный кабинет - Образовательный курс";
require_once(__DIR__ . "/../database/dbconnect.php");

$user_id = $_SESSION['userID'];


// Получаем данные пользователя
$stmt = $dbcon->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
	session_destroy();
	header("Location: login.php");
	exit;
}

// Получаем пройденные тесты
$stmt_tests = $dbcon->prepare("
        SELECT ct.*, t.title_test 
        FROM completed_test ct
        JOIN test t ON ct.id_test = t.id
        WHERE ct.id_student = ?
        ORDER BY ct.date_completed DESC
    ");
$stmt_tests->bind_param("i", $user_id);
$stmt_tests->execute();
$result_tests = $stmt_tests->get_result();
$tests = [];

while ($row = $result_tests->fetch_assoc()) {
	$tests[] = $row;
}

// Считаем общее количество лекций
$stmt_lectures = $dbcon->prepare("SELECT COUNT(*) as total_lectures FROM lecture");
$stmt_lectures->execute();
$result_lectures = $stmt_lectures->get_result();
$row_lectures = $result_lectures->fetch_assoc();
$total_lectures = $row_lectures['total_lectures'];

// Получаем количество пройденных лекций
$completed_lectures = $user['progress'];

// Вычисляем процент прогресса
$progress_percent = 0;
if ($total_lectures > 0) {
    $progress_percent = round(($completed_lectures / $total_lectures) * 100, 2);
}

// Формируем текстовое описание прогресса
$progress_text = "$completed_lectures из $total_lectures";