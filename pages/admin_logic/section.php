<?php
$dbcon = new mysqli("localhost", "root", "", "diplom");

if ($dbcon->connect_errno) {
	printf("Не удалось подключиться: %s\n", $dbcon->connect_error);
	exit();
}

// Обработка действия (add, edit, delete, getSections, getSection, checkSection)
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

switch ($action) {
	case 'add':
		addSection($dbcon);
		break;
	case 'edit':
		editSection($dbcon);
		break;
	case 'delete':
		deleteSection($dbcon);
		break;
	case 'getSections':
		getSections($dbcon);
		break;
	case 'getSection':
		getSection($dbcon);
		break;
	case 'checkSection':
		checkSection($dbcon);
		break;
	case 'moveLectures':
		moveLectures($dbcon);
		break;
	case 'deleteLectures':
		deleteLectures($dbcon);
		break;
	default:
		echo "Недопустимое действие";
}

$dbcon->close();

// Функции для работы с разделами

function addSection($dbcon)
{
	$title_section = $dbcon->real_escape_string($_POST['sectionName']);
	$order_section = intval($_POST['sectionOrder']);

	$sql = "INSERT INTO section (title_section, order_section) VALUES ('$title_section', $order_section)";

	if ($dbcon->query($sql) === TRUE) {
		echo "Раздел успешно добавлен";
	} else {
		echo "Ошибка при добавлении раздела: " . $dbcon->error;
	}
}

function editSection($dbcon)
{
	$id = intval($_POST['editSectionId']);
	$title_section = $dbcon->real_escape_string($_POST['editSectionName']);
	$order_section = intval($_POST['editSectionOrder']);

	$sql = "UPDATE section SET title_section = '$title_section', order_section = $order_section WHERE id = $id";

	if ($dbcon->query($sql) === TRUE) {
		echo "Раздел успешно обновлен";
	} else {
		echo "Ошибка при обновлении раздела: " . $dbcon->error;
	}
}


function checkSection($dbcon)
{
	$id = intval($_POST['sectionId']);

	$sql = "SELECT COUNT(*) FROM lecture WHERE id_section = $id";
	$result = $dbcon->query($sql);

	if ($result && $result->num_rows > 0) {
		$row = $result->fetch_row();
		$lectureCount = $row[0];

		if ($lectureCount > 0) {
			echo json_encode(['hasLectures' => true, 'lectureCount' => $lectureCount]);
		} else {
			echo json_encode(['hasLectures' => false]);
		}
	} else {
		echo json_encode(['error' => 'Ошибка при проверке наличия лекций: ' . $dbcon->error]);
	}
}

function deleteSection($dbcon)
{
	$id = intval($_POST['sectionId']);

	$sql = "DELETE FROM section WHERE id = $id";

	if ($dbcon->query($sql) === TRUE) {
		echo "Раздел успешно удален";
	} else {
		echo "Ошибка при удалении раздела: " . $dbcon->error;
	}
}

function moveLectures($dbcon)
{
	$sectionId = intval($_POST['sectionId']);
	$newSectionId = intval($_POST['newSectionId']);

	$sql = "UPDATE lecture SET id_section = $newSectionId WHERE id_section = $sectionId";

	if ($dbcon->query($sql) === TRUE) {
		echo "Лекции успешно перемещены в другой раздел.";
	} else {
		echo "Ошибка при перемещении лекций: " . $dbcon->error;
	}
}

function deleteLectures($dbcon)
{
	$sectionId = intval($_POST['sectionId']);

	$sql = "DELETE FROM lecture WHERE id_section = $sectionId";

	if ($dbcon->query($sql) === TRUE) {
		echo "Лекции успешно удалены.";
	} else {
		echo "Ошибка при удалении лекций: " . $dbcon->error;
	}
}

function getSections($dbcon)
{
	$sql = "SELECT id, title_section AS name, order_section AS 'order' FROM section ORDER BY order_section";
	$result = $dbcon->query($sql);

	if ($result->num_rows > 0) {
		$sections = array();
		while ($row = $result->fetch_assoc()) {
			$sections[] = $row;
		}
		echo json_encode($sections);
	} else {
		echo "[]"; // Возвращаем пустой массив, если разделов нет
	}
}

function getSection($dbcon)
{
	$id = intval($_GET['id']);

	$sql = "SELECT id, title_section AS name, order_section AS 'order' FROM section WHERE id = $id";
	$result = $dbcon->query($sql);

	if ($result->num_rows == 1) {
		$section = $result->fetch_assoc();
		echo json_encode($section);
	} else {
		echo "null"; // Или можно вернуть JSON с сообщением об ошибке
	}
}
