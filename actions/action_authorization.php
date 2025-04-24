<?php
session_start();
require_once("../database/dbconnect.php");

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
    exit;
}

// Проверяем наличие параметров
if (!isset($_POST['surname']) || !isset($_POST['password'])) {
    echo json_encode(['success' => false, 'message' => 'Отсутствуют необходимые параметры']);
    exit;
}

// Создаем переменные
$surname = $_POST['surname'];
$password = $_POST['password'];

// Подготовленное выражение для защиты от SQL-инъекций
$mysql = "SELECT * FROM users WHERE surname = ? AND password = ?";
$stmt = $dbcon->prepare($mysql);

if (!$stmt) {
    // Если запрос не может быть подготовлен
    echo json_encode(['success' => false, 'message' => 'Ошибка подготовки запроса: ' . $dbcon->error]);
    exit;
}

// Привязываем параметры
$stmt->bind_param("ss", $surname, $password);

// Выполняем запрос
if (!$stmt->execute()) {
    // Если запрос не выполнен успешно
    echo json_encode(['success' => false, 'message' => 'Ошибка выполнения запроса: ' . $stmt->error]);
    $stmt->close();
    exit;
}

// Получаем результат
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    // Если пользователь найден
    $mass = $result->fetch_assoc();

    // Сохраняем данные пользователя в сессию
    $_SESSION['userID'] = $mass['id'];
    $_SESSION['name'] = $mass['name'];
    $_SESSION['surname'] = $mass['surname'];
    $_SESSION['group_st'] = $mass['group_st'];
    $_SESSION['progress'] = $mass['progress'];
    $_SESSION['points'] = $mass['points'];
    $_SESSION['role'] = $mass['role'];
    $_SESSION['authorization_dostup'] = true;

    if ($mass['role'] == 'admin') {
        $_SESSION['authorization_dostup_role'] = "admin";
        echo json_encode(['success' => true, 'role' => 'admin']);
    } else {
        $_SESSION['authorization_dostup_role'] = "user";
        echo json_encode(['success' => true, 'role' => 'user']);
    }
} else {
    // Если пользователь не найден
    echo json_encode(['success' => false, 'message' => 'Такого пользователя нет. Пожалуйста, зарегистрируйтесь.']);
}

// Закрываем подготовленное выражение
$stmt->close();
?>