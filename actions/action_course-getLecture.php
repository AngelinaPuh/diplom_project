<?php
// diplom_project\actions/action_course-getLecture.php
require_once("../database/dbconnect.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $lectureId = intval($_GET['id']); // Защита от SQL-инъекций

    // Запрос для получения текста лекции
    $query = "SELECT lecture_text FROM lecture WHERE id = ?";
    $stmt = $dbcon->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Ошибка подготовки запроса']);
        exit;
    }

    $stmt->bind_param("i", $lectureId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $lecture = $result->fetch_assoc();
        echo json_encode(['success' => true, 'text' => $lecture['lecture_text']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Лекция не найдена']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Неверный запрос']);
}
?>