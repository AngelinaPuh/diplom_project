<?php
// diplom_project/actions/action_complete_lecture.php
session_start();
require_once(__DIR__ . "/../database/dbconnect.php");


header('Content-Type: application/json');

if (!isset($_SESSION['authorization_dostup']) || $_SESSION['authorization_dostup'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit;
}

$userId = $_SESSION['userID']; // Предполагается, что вы сохраняете ID пользователя в сессии
$lectureId = intval($_POST['lecture_id']);


// Вызываем функцию один раз и получаем результат
$isFirstTime = completeLecture($userId, $lectureId, $dbcon);

echo json_encode([
    'success' => true,
    'isFirstTime' => $isFirstTime,
    'message' => '+1 к прогрессу прохождения курса. Так держать!'
]);

// Функция возвращает true только при первом завершении лекции
function completeLecture($userId, $lectureId, $dbcon) {
    // Проверяем, есть ли уже такая запись
    $checkQuery = "SELECT * FROM completed_lecture WHERE user_id = ? AND lecture_id = ?";
    $stmt = $dbcon->prepare($checkQuery);
    $stmt->bind_param("ii", $userId, $lectureId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Лекция ещё не завершена — добавляем запись
        $insertQuery = "INSERT INTO completed_lecture (user_id, lecture_id) VALUES (?, ?)";
        $stmtInsert = $dbcon->prepare($insertQuery);
        $stmtInsert->bind_param("ii", $userId, $lectureId);
        $stmtInsert->execute();

        // Увеличиваем прогресс пользователя
        $updateProgressQuery = "UPDATE users SET progress = progress + 1 WHERE id = ?";
        $stmtUpdate = $dbcon->prepare($updateProgressQuery);
        $stmtUpdate->bind_param("i", $userId);
        $stmtUpdate->execute();

        return true; // Первый раз
    }

    return false; // Уже была отмечена
}

?>