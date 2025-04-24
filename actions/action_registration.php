<?php
session_start();
require_once('../database/dbconnect.php');

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
    exit;
}

// Проверяем наличие параметров
if (
    !isset($_POST['surname']) || !isset($_POST['name']) ||
    !isset($_POST['group']) || !isset($_POST['password']) ||
    !isset($_POST['password_check'])
) {
    echo json_encode(['success' => false, 'message' => 'Отсутствуют необходимые параметры']);
    exit;
}

// Создаем переменные
$surname = $_POST['surname'];
$name = $_POST['name'];
$group = $_POST['group'];
$password = $_POST['password'];
$password_check = $_POST['password_check'];

// Проверяем совпадение паролей
if ($password !== $password_check) {
    echo json_encode(['success' => false, 'message' => 'Пароли не совпадают']);
    exit;
}

// Проверяем, существует ли пользователь с такой фамилией
$checkUserQuery = "SELECT * FROM users WHERE surname = ?";
$stmt = $dbcon->prepare($checkUserQuery);
$stmt->bind_param('s', $surname);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Пользователь с такой фамилией уже зарегистрирован']);
    $stmt->close();
    exit;
}

// Добавляем нового пользователя в базу данных
$insertUserQuery = "INSERT INTO users (surname, name, group_st, password, progress, points, role) VALUES (?, ?, ?, ?, 0, 0, 'user')";
$stmt = $dbcon->prepare($insertUserQuery);
$stmt->bind_param('ssss', $surname, $name, $group, $password);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Регистрация прошла успешно']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при регистрации: ' . $stmt->error]);
}

$stmt->close();
?>