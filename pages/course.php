<?php

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
                <article id="lecture-content-<?php echo $lecture['id']; ?>">
                    <!-- Здесь будет загружен текст лекции -->
                    <p>Загрузка...</p>
                </article>
                <div class="course__footer">
                    <button class="course__complete-lecture" data-lecture-id="<?php echo $lecture['id']; ?>">Завершить лекцию</button>
                    <button class="course__take-test" data-lecture-id="<?php echo $lecture['id']; ?>" style="display: none;">Пройти тест</button>
                    <button class="course__next-lecture" data-lecture-id="<?php echo $lecture['id']; ?>">Следующая лекция</button>
                </div>
                <hr class="separator">
                <!-- блок теста -->
                <div class="test-block" data-lecture-id="<?php echo $lecture['id']; ?>" style="display: none; margin-top: 20px;">
                    <?php if (!empty($lecture['test_questions'])): ?>
                        <h3>Тест по теме <?php echo htmlspecialchars($lecture['title_lecture']); ?></h3>
                        <form class="test-form" data-lecture-id="<?php echo $lecture['id']; ?>">
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
                        </form>
                        <!-- Блок для вывода результатов -->
                        <div class="test-results" style="margin-top: 20px; display: none;">
                            <p  class="results-p"><strong>Результат:</strong> <span class="result-message"></span></p>
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
?>