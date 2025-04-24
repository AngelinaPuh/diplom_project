<!-- 4. Оптимизация производительности
 и еще "данные в таблицах sections и lectures редко меняются, можно использовать кэширование. Например, сохранять результаты запросов в файл или использовать Redis/Memcached." это ты мне написал, думаю так и будет, поэтому сделай это-->

<?php
$pageTitle = "Учебник - Образовательный курс"; // Заголовок для страницы учебника
?>
<!-- Главный контейнер -->
<?php
// Запрос для получения всех разделов
$sectionsQuery = "SELECT id, title_section FROM section ORDER BY id ASC";
$sectionsResult = $dbcon->query($sectionsQuery);

// Проверяем, успешно ли выполнен запрос
if ($sectionsResult === false) {
    die("Ошибка при получении разделов: " . $dbcon->error);
}

// Преобразуем результат в массив
$sections = $sectionsResult->fetch_all(MYSQLI_ASSOC);

// Запрос для получения всех лекций
$lecturesQuery = "SELECT id, id_section, title_lecture FROM lecture ORDER BY id_section ASC, id ASC";
$lecturesResult = $dbcon->query($lecturesQuery);

// Проверяем, успешно ли выполнен запрос
if ($lecturesResult === false) {
    die("Ошибка при получении лекций: " . $dbcon->error);
}

// Преобразуем результат в массив
$lectures = $lecturesResult->fetch_all(MYSQLI_ASSOC);

// Группируем лекции по разделам
$lecturesBySection = [];
foreach ($lectures as $lecture) {
    $lecturesBySection[$lecture['id_section']][] = $lecture;
}
?>
<div class="course__container">
    <!-- блок сайдбара -->
    <div class="course__sidebar">
        <h3>Архитектура компьютерных систем</h3>
        <ul class="course__accordion">
        <?php foreach ($sections as $section): ?>
            <li>
                <button class="course__accordion-section"><?php echo htmlspecialchars($section['title_section']); ?></button>
                <ul class="course__accordion-lecture">
                    <?php if (isset($lecturesBySection[$section['id']])): ?>
                        <?php foreach ($lecturesBySection[$section['id']] as $lecture): ?>
                            <li>
                                <a href="#lecture-<?php echo $lecture['id']; ?>" class="course__accordion-link">
                                    <?php echo htmlspecialchars($lecture['title_lecture']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
    <!-- блок контента -->
    <div class="course__content">
    <?php foreach ($lectures as $lecture): ?>
        <section id="lecture-<?php echo $lecture['id']; ?>" class="cours__lecture" style="display: none;">
            <div class="cours__lecture-header">
                <h2><?php echo htmlspecialchars($lecture['title_lecture']); ?></h2>
            </div>
            <article id="lecture-content-<?php echo $lecture['id']; ?>">
                <!-- Здесь будет загружен текст лекции -->
                <p>Загрузка...</p>
            </article>
            <div class="course__footer">
                <button id="course__complete-lecture">Завершить лекцию</button>
                <button id="course__take-test" style="display: none;">Пройти тест</button>
                <button id="course__next-lecture">Следующая лекция</button>
            </div>
        </section>
    <?php endforeach; ?>
    </div>
</div>