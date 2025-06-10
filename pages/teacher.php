<?php
include_once 'teacher_logic.php';
$pageTitle = "Личный кабинет пе - Образовательный курс"; // Заголовок для главной страницы
?>

<div class="container">
	<div class="user__div">
		<h2>Добро пожаловать, преподаватель <?= htmlspecialchars($user['surname']) ?> <?= htmlspecialchars($user['name']) ?></h2>
		<button type="button" class="open__admin" onclick="window.open('pages/admin_panel.php', '_blank')">Открыть админ-панель</button>
		<form action="actions/exit.php" method="post">
			<button type="submit" class="logout-btn">Выйти</button>
		</form>
	</div>
	<!-- Таблица со студентами и их тестами -->
	<div class="students-test-results">
		<h3>Результаты студентов по тестам</h3>
		<button type="button" id="resetFiltersBtn" class="reset-filters-btn">Сбросить фильтры</button>
		<?php if (!empty($students_with_tests)): ?>
			<table id="resultsTable" class="styled-table">
				<thead>
					<tr>
						<!-- Группа -->
						<th>
							<div class="custom-select" id="groupFilter">
								<div class="selected-option">Группа</div>
								<div class="select-options">
									<div class="option" data-value="">Все группы</div>
									<?php foreach ($groups as $group): ?>
										<div class="option" data-value="<?= htmlspecialchars($group) ?>"><?= htmlspecialchars($group) ?></div>
									<?php endforeach; ?>
								</div>
							</div>
						</th>

						<!-- Студент -->
						<th>
							<div class="custom-select" id="studentFilter">
								<div class="selected-option">Студент</div>
								<div class="select-options">
									<div class="option" data-value="default">По умолчанию</div>
									<div class="option" data-value="asc">А → Я</div>
									<div class="option" data-value="desc">Я → А</div>
								</div>
							</div>
						</th>

						<!-- Тема теста -->
						<th>
							<div class="custom-select" id="testFilter">
								<div class="selected-option">Тема теста</div>
								<div class="select-options">
									<div class="option" data-value="">Все темы</div>
									<?php
									// Получаем все уникальные темы
									$stmt_topics = $dbcon->prepare("SELECT DISTINCT title_test FROM test");
									$stmt_topics->execute();
									$result_topics = $stmt_topics->get_result();

									while ($row = $result_topics->fetch_assoc()):
									?>
										<div class="option" data-value="<?= htmlspecialchars($row['title_test']) ?>">
											<?= htmlspecialchars($row['title_test']) ?>
										</div>
									<?php endwhile; ?>
								</div>
							</div>
						</th>

						<!-- Оценка -->
						<th>
							<div class="custom-select" id="gradeFilter">
								<div class="selected-option">Оценка</div>
								<div class="select-options">
									<div class="option" data-value="">Все оценки</div>
									<div class="option" data-value="5">5</div>
									<div class="option" data-value="4">4</div>
									<div class="option" data-value="3">3</div>
									<div class="option" data-value="2">2</div>
								</div>
							</div>
						</th>

						<!-- Попытка -->
						<th>Попытка</th>

						<!-- Дата прохождения -->
						<th>
							<div class="custom-select" id="dateFilter">
								<div class="selected-option">Дата прохождения</div>
								<div class="select-options">
									<div class="option" data-value="default">По умолчанию</div>
									<div class="option" data-value="newest">Новые → старые</div>
									<div class="option" data-value="oldest">Старые → новые</div>
								</div>
							</div>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($students_with_tests as $student): ?>
						<?php foreach ($student['tests'] as $test): ?>
							<tr data-group="<?= htmlspecialchars($student['group_st']) ?>">
								<td><?= htmlspecialchars($student['group_st']) ?></td>
								<td><?= htmlspecialchars($student['surname']) ?> <?= htmlspecialchars($student['name']) ?></td>
								<td><?= htmlspecialchars($test['title_test']) ?></td>
								<td><?= htmlspecialchars($test['assessment']) ?></td>
								<td><?= htmlspecialchars($test['try']) ?></td>
								<td><?= htmlspecialchars($test['date_completed']) ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<p>Нет данных о результатах студентов.</p>
		<?php endif; ?>
	</div>
</div>