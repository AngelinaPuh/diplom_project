<?php 
    // все ощибки записываются в файл, это чтобы пользователь не видел какие-то ошибки
    // ini_set('log_errors', 1); // Включает логирование ошибок
    // ini_set('error_log', 'z-something/error.log'); // Укажите путь к файлу для логирования
    
    
    // нужен при разработке чтобы выводил ошибки 
//  ПОТОМ УБРАТЬ ЧТОБЫ ПОЛЬЗОВАТЕЛИ НЕ ВИДЕЛИ
    error_reporting(E_ALL); // Включает все уровни ошибок
    ini_set('display_errors', 1); // Включает отображение ошибок на экране

    session_start(); 
    require_once("database/dbconnect.php"); 

    // Инициализируем заголовок по умолчанию
    $pageTitle = "Образовательный курс";

    // Подключаем main_container.php и получаем заголовок
    ob_start(); // Включаем буферизацию вывода
    include "includes/main_container.php";
    $content = ob_get_clean(); // Получаем содержимое контейнера

    // Если заголовок был изменен в main_container.php
    if (isset($newPageTitle)) {
        $pageTitle = $newPageTitle;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Стили -->
    <link rel="stylesheet" href="style/style_main.css"><!--  Стили основной страницы ьез под страниц-->
    <link rel="stylesheet" href="style/style_home.css"> <!--Стили домашней страницы--> 
    <link rel="stylesheet" href="style/style_course.css"><!-- Стили для учебника(разделов, лекций, тестов), сайдбара -->
    <link rel="stylesheet" href="style/style_profile.css"><!--Стили для личного кабинета -->
    <link rel="stylesheet" href="style/style_authorization.css"><!--Стили для авторизации и регистрации -->
    <link rel="stylesheet" href="style/style_lecture.css"><!--Стили для cамих лекций-->
    <link rel="stylesheet" href="style/style_teacher.css"><!--Стили для личного кабинета преподавтеля и админки -->
    <!-- Иконка -->
    <link rel="icon" href="images/favicon.png" type="image/x-icon">
</head>
<body>
    <div class="wrapper">
        <header>
            <?php require "includes/header.php"; ?>
        </header>
        <main>
            <?php echo $content; ?>
        </main>
        <footer> 
            <?php require "includes/footer.php"; ?>
        </footer>
    </div>
    <?php include 'pages/authorization.php'; ?> <!-- Включение модального окна -->
    <script src="scripts/scripts_auth.js" defer></script>
    <script src="scripts/scripts_course.js"></script>
    <script src="scripts/scripts_teacher.js"></script>
</body>
</html>