<?php
// diplom_project/pages/teacher_logic.php
require_once(__DIR__ . "/../database/dbconnect.php");

// Получаем ID преподавателя
$teacher_id = $_SESSION['userID'];

// Получаем данные преподавателя
$stmt_teacher = $dbcon->prepare("SELECT * FROM users WHERE id = ?");
$stmt_teacher->bind_param("i", $teacher_id);
$stmt_teacher->execute();
$result_teacher = $stmt_teacher->get_result();
$user = $result_teacher->fetch_assoc();

// Получаем список всех студентов (пользователи с ролью user)
$stmt_students = $dbcon->prepare("
    SELECT id, surname, name, group_st 
    FROM users 
    WHERE role = 'user'
");
$stmt_students->execute();
$result_students = $stmt_students->get_result();

$students = [];
while ($row = $result_students->fetch_assoc()) {
	$students[] = $row;
}

// Для каждого студента получаем информацию о тестах
$students_with_tests = [];

foreach ($students as $student) {
	$student_id = $student['id'];

	// Получаем тесты студента
	$stmt_tests = $dbcon->prepare("
        SELECT ct.*, t.title_test 
        FROM completed_test ct
        JOIN test t ON ct.id_test = t.id
        WHERE ct.id_student = ?
        ORDER BY ct.date_completed DESC
    ");
	$stmt_tests->bind_param("i", $student_id);
	$stmt_tests->execute();
	$result_tests = $stmt_tests->get_result();

	$tests = [];
	while ($test_row = $result_tests->fetch_assoc()) {
		$tests[] = $test_row;
	}

	// Добавляем только тех студентов, у которых есть тесты
	if (!empty($tests)) {
		$student['tests'] = $tests;
		$students_with_tests[] = $student;
	}
}

// Получаем список уникальных групп для фильтрации
$stmt_groups = $dbcon->prepare("SELECT DISTINCT group_st FROM users WHERE role = 'user' AND group_st IS NOT NULL ORDER BY group_st");
$stmt_groups->execute();
$result_groups = $stmt_groups->get_result();

$groups = [];
while ($row = $result_groups->fetch_assoc()) {
	$groups[] = $row['group_st'];
}
