<!-- 4. Оптимизация производительности
 и еще "данные в таблицах sections и lectures редко меняются, можно использовать кэширование. Например, сохранять результаты запросов в файл или использовать Redis/Memcached." это ты мне написал, думаю так и будет, поэтому сделай это-->

<?php
// $pageTitle = "Учебник - Образовательный курс"; // Заголовок для страницы учебника
// // Запрос для получения всех разделов
// $sectionsQuery = "SELECT id, title_section FROM section ORDER BY id ASC";
// $sectionsResult = $dbcon->query($sectionsQuery);

// // Проверяем, успешно ли выполнен запрос
// if ($sectionsResult === false) {
//     die("Ошибка при получении разделов: " . $dbcon->error);
// }

// // Преобразуем результат в массив
// $sections = $sectionsResult->fetch_all(MYSQLI_ASSOC);

// // Запрос для получения всех лекций
// $lecturesQuery = "SELECT id, id_section, title_lecture FROM lecture ORDER BY id_section ASC, id ASC";
// $lecturesResult = $dbcon->query($lecturesQuery);

// // Проверяем, успешно ли выполнен запрос
// if ($lecturesResult === false) {
//     die("Ошибка при получении лекций: " . $dbcon->error);
// }

// // Преобразуем результат в массив
// $lectures = $lecturesResult->fetch_all(MYSQLI_ASSOC);

// // Группируем лекции по разделам
// $lecturesBySection = [];
// foreach ($lectures as $lecture) {
//     $lecturesBySection[$lecture['id_section']][] = $lecture;
// }
// Функция для получения данных из кэша
// Заголовок для страницы учебника
$pageTitle = "Учебник - Образовательный курс";

// Функция для получения данных из кэша
// В начале файла
$startTime = microtime(true);
// Функция для получения данных из кэша
function getCache($key) {
    $cacheFile = __DIR__ . '/cache/' . md5($key) . '.cache';
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 3600) { // Кэш живет 1 час
        echo "Данные взяты из кэша для ключа: $key<br>";
        return json_decode(file_get_contents($cacheFile), true);
    }
    echo "Кэш не найден для ключа: $key. Выполняется запрос к базе данных.<br>";
    return false;
}

// Функция для сохранения данных в кэш
function setCache($key, $data, $ttl = 3600) {
    $cacheDir = __DIR__ . '/cache/';
    if (!is_dir($cacheDir)) {
        if (!mkdir($cacheDir, 0777, true)) {
            die("Не удалось создать папку кэша: $cacheDir");
        }
    }

    $cacheFile = $cacheDir . md5($key) . '.cache';
    if (file_put_contents($cacheFile, json_encode($data)) === false) {
        die("Не удалось записать файл кэша: $cacheFile");
    }
    echo "Данные сохранены в кэш для ключа: $key<br>";
}

// Функция для очистки кэша
function clearCache($key) {
    $cacheFile = __DIR__ . '/cache' . md5($key) . '.cache';
    if (file_exists($cacheFile)) {
        unlink($cacheFile);
    }
}

// Проверяем кэш для разделов
$sectionsCacheKey = 'sections_data';
$sections = getCache($sectionsCacheKey);

if (!$sections) {
    // Запрос для получения всех разделов
    $sectionsQuery = "SELECT id, title_section FROM section ORDER BY id ASC";
    $sectionsResult = $dbcon->query($sectionsQuery);

    echo "Кэш для разделов не найден. Данные загружены из базы данных.<br>";

    // Проверяем, успешно ли выполнен запрос
    if ($sectionsResult === false) {
    echo "Данные для разделов загружены из кэша.<br>";
        die("Ошибка при получении разделов: " . $dbcon->error);
    }

    // Преобразуем результат в массив
    $sections = $sectionsResult->fetch_all(MYSQLI_ASSOC);

    // Сохраняем данные в кэш
    setCache($sectionsCacheKey, $sections);
}

// Проверяем кэш для лекций
$lecturesCacheKey = 'lectures_data';
$lectures = getCache($lecturesCacheKey);

if (!$lectures) {
    // Запрос для получения всех лекций
    $lecturesQuery = "SELECT id, id_section, title_lecture FROM lecture ORDER BY id_section ASC, id ASC";
    $lecturesResult = $dbcon->query($lecturesQuery);
    echo "Кэш для лекций не найден. Данные загружены из базы данных.<br>";

    // Проверяем, успешно ли выполнен запрос
    if ($lecturesResult === false) {
        echo "Данные для лекций загружены из кэша.<br>";
        die("Ошибка при получении лекций: " . $dbcon->error);
    }

    // Преобразуем результат в массив
    $lectures = $lecturesResult->fetch_all(MYSQLI_ASSOC);

    // Сохраняем данные в кэш
    setCache($lecturesCacheKey, $lectures);
}

// Группируем лекции по разделам
$lecturesBySection = [];
foreach ($lectures as $lecture) {
    $lecturesBySection[$lecture['id_section']][] = $lecture;
}
?>

<!-- Главный контейнер -->
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
<?php

// В конце файла
$endTime = microtime(true);
echo "<br>Время выполнения скрипта: " . round(($endTime - $startTime), 4) . " секунд.";
?>