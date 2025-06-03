<?php
include_once 'teacher_logic.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет преподавателя</title>
</head>
<body>

<div class="container">
    <div class="user__div">
        <h2>Добро пожаловать, преподаватель <?= htmlspecialchars($user['surname']) ?> <?= htmlspecialchars($user['name']) ?></h2>		
		<button type="button" class="open__admin">Открыть панель управления лекциями</button>
        <form action="actions/exit.php" method="post">
            <button type="submit" class="logout-btn">Выйти</button>
        </form>
    </div>
    <!-- Таблица со студентами и их тестами -->
    <div class="students-test-results">
        <h3>Результаты студентов по тестам</h3>

        <?php if (!empty($students_with_tests)): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Группа</th>
                        <th>Фамилия Имя</th>
                        <th>Тема теста</th>
                        <th>Оценка</th>
                        <th>Попытка</th>
                        <th>Дата прохождения</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students_with_tests as $student): ?>
                        <?php foreach ($student['tests'] as $test): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['group_st']) ?></td>
                                <td><?= htmlspecialchars($student['surname']) ?> <?= htmlspecialchars($student['name']) ?></td>
                                <td><?= htmlspecialchars($test['title_test']) ?></td>
                                <td><?= htmlspecialchars($test['assessment']) ?></td>
                                <td><?= htmlspecialchars($test['try']) ?></td>
                                <td><?= htmlspecialchars($test['date_completed']) ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Если у студента нет тестов -->
                        <?php if (empty($student['tests'])): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['group_st']) ?></td>
                                <td><?= htmlspecialchars($student['surname']) ?> <?= htmlspecialchars($student['name']) ?></td>
                                <td colspan="4">Нет данных о тестах</td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Нет студентов для отображения.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>