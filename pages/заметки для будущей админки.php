<?php
// // clear_cache.php
// <a href="clear_cache.php" class="btn">Очистить весь кэш</a>

// После обновления теста для лекции
// clearTestCache($lectureId);

// // После добавления новой лекции
// clearLecturesCache();

// // После изменения разделов
// clearSectionsCache();

// // написать правильный путь потом
// require_once 'course_logic/cache_functions.php';

// // Очищаем кэш разделов
// clearSectionsCache();

// // Очищаем кэш лекций
// clearLecturesCache();

// // Очищаем кэш тестов (для всех лекций)
// $lectures = getLectures(); // Предполагается, что такая функция существует
// foreach ($lectures as $lecture) {
//     clearTestCache($lecture['id']);
// }

// echo "Кэш успешно очищен!";