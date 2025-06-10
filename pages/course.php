<?php
// diplom_project\pages\course.php
// Подключаем логику
require_once 'course_logic/cache_functions.php';
require_once 'course_logic/logic.php';
?>
<script>
    // Передаем состояние авторизации в JavaScript
    const isUserAuthorized = <?php echo $isAuthorized ? 'true' : 'false'; ?>;
</script>

<!-- Главный контейнер -->
<div class="course__container">
    <!-- блок сайдбара -->
    <div class="course__sidebar">
        <h3>Архитектура компьютерных систем</h3>
        <ul class="course__accordion">
            <?php foreach ($sections as $section): ?>
                <li>
                    <button class="course__accordion-section" data-state="collapsed">
                        <?php echo htmlspecialchars($section['title_section']); ?>
                    </button>
                    <ul class="course__accordion-lecture" style="display: none;">
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
    <!-- Блок контента -->
    <div class="course__content">
        <?php foreach ($allLectures as $index => $lecture): ?>
            <section
                id="lecture-<?php echo $lecture['id']; ?>"
                class="cours__lecture"
                style="display: none;"
                data-next-lecture-id="<?php echo $index + 1 < count($allLectures) ? $allLectures[$index + 1]['id'] : ''; ?>">
                <!-- Блок для загрузки контента лекции -->
                <article id="lecture-content-<?php echo $lecture['id']; ?>">
                    <!-- Индикатор загрузки -->
                    <div class="lecture-loading-indicator" style="display: block; text-align: center;">
                        <p>Загрузка...</p>
                        <div class="spinner"></div>
                    </div>
                    <!-- Здесь будет загружен текст лекции -->
                    <p style="display: none;">Содержимое лекции</p>
                </article>
                <div class="course__footer">
                    <button class="course__complete-lecture" data-lecture-id="<?php echo $lecture['id']; ?>">Завершить лекцию</button>
                    <!-- Кнопка "Пройти тест" или сообщение будут добавлены динамически -->
                    <button class="course__next-lecture" data-lecture-id="<?php echo $lecture['id']; ?>">Следующая лекция</button>
                </div>
                <!-- блок теста -->
                <div class="test-block" data-lecture-id="<?php echo $lecture['id']; ?>" style="display: none; margin-top: 20px;">
                    <h3 class="test-attempt-info">
                        У вас есть всего 3 попытки пройти тест. Будьте очень внимательны.
                    </h3>
                    <hr class="separator">
                    <p class="test-attempts-left">
                        У вас есть еще <span class="attempts-count">N</span> попыток
                    </p>
                    <p class="no-attempts-message" style="color: red; display: none;">
                        Упс! У вас не осталось попыток.
                    </p>
                    <hr class="separator">
                    <?php if (!empty($lecture['test_questions'])): ?>

                        <div class="test-loading-indicator" style="display: none;">
                            <div class="spinner"></div>
                            <p>Загрузка теста...</p>
                        </div>
                        <form class="test-form" data-lecture-id="<?php echo $lecture['id']; ?>">
                            <h3>Тест по теме <?php echo htmlspecialchars($lecture['title_lecture']); ?></h3>
                            <input type="hidden" name="lecture_id" value="<?php echo $lecture['id']; ?>">
                            <?php foreach ($lecture['test_questions'] as $question): ?>
                                <div class="question-block">
                                    <h3 class="question-title">Вопрос: <?php echo htmlspecialchars($question['title']); ?></h3>
                                    <hr class="separator">
                                    <div class="test__form-block">
                                        <?php foreach ($question['options'] as $option): ?>
                                            <label class="answer-option">
                                                <input required type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo htmlspecialchars($option); ?>">
                                                <?php echo htmlspecialchars($option); ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <button type="button" class="submit-test btn-form">Отправить тест</button>
                            <div class="test-loading-indicator" style="display: none;">
                                <div class="spinner"></div>
                                <p>Проверка результатов...</p>
                            </div>
                        </form>
                        <!-- Блок для вывода результатов -->
                        <div class="test-results" style="margin-top: 20px; display: none;">
                            <p class="results-p"><strong>Результат:</strong> <span class="result-message"></span></p>
                            <!-- Блок с оценкой -->
                            <div class="grade-block">
                                <span class="grade-number"></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <p>Тест для этой лекции отсутствует.</p>
                    <?php endif; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

</div>
<?php
// В конце файла
$endTime = microtime(true);
// echo "<br>Время выполнения скрипта: " . round(($endTime - $startTime), 4) . " секунд.";
// echo "<br>Загружено из кэша: " . ($isLoadedFromCache ? 'Да' : 'Нет');
?>
<script>
    const isLoadedFromCache = <?php echo $isLoadedFromCache ? 'true' : 'false'; ?>;
    // document.addEventListener('DOMContentLoaded', function() {
    //     const cacheStatus = document.createElement('div');
    //     cacheStatus.style.position = 'fixed';
    //     cacheStatus.style.bottom = '10px';
    //     cacheStatus.style.right = '10px';
    //     cacheStatus.style.padding = '10px';
    //     cacheStatus.style.background = '#f0f0f0';
    //     cacheStatus.style.border = '1px solid #ccc';
    //     cacheStatus.style.borderRadius = '5px';
    //     cacheStatus.textContent = isLoadedFromCache ?
    //     'Данные загружены из кэша' :
    //     'Данные загружены из базы данных';
    //     document.body.appendChild(cacheStatus);

    //     // Удалить сообщение через 5 секунд
    //     setTimeout(() => {
    //         document.body.removeChild(cacheStatus);
    //     }, 5000);
    // });
</script>