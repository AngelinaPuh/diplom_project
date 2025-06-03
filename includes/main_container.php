<div class="main__container">
    <?php 
        // Устанавливаем глобальную переменную для заголовка
        global $newPageTitle;
        if (isset($_GET['page'])) {
            $allowed_pages = ['home', 'course', 'profile', 'authorization', 'teacher'];
            $page = basename($_GET['page']);

            if (in_array($page, $allowed_pages)) {
                // Проверка для страницы profile: редирект по роли
                if ($page === 'profile') {
                    if (isset($_SESSION['authorization_dostup']) && $_SESSION['authorization_dostup'] === true) {
                        // Пользователь авторизован — смотрим роль
                        if ($_SESSION['authorization_dostup_role'] === 'admin') {
                            // Редирект на учительскую страницу
                            header("Location: index.php?page=teacher");
                            exit;
                        }
                    } else {
                        // Не авторизован — нельзя на профиль
                        echo "<p>Доступ запрещён. Войдите в систему.</p>";
                        $newPageTitle = "Ошибка доступа - Образовательный курс";
                        exit;
                    }
                }

                // Проверка доступа к teacher только для админа
                if ($page === 'teacher') {
                    if (!(isset($_SESSION['authorization_dostup']) && $_SESSION['authorization_dostup_role'] === 'admin')) {
                        // Только админ может видеть эту страницу
                        echo "<p>Доступ запрещён. Только администратор.</p>";
                        $newPageTitle = "Ошибка доступа - Образовательный курс";
                        exit;
                    }
                }
                // Подключаем соответствующий файл
                include "pages/$page.php";

                // Устанавливаем заголовок на основе содержимого страницы
                switch ($page) {
                    case 'home':
                        $newPageTitle = "Главная страница - Образовательный курс";
                        break;
                    case 'course':
                        $newPageTitle = "Учебник - Образовательный курс";
                        break;
                    case 'profile':
                        $newPageTitle = "Профиль пользователя - Образовательный курс";
                        break;
                    case 'authorization':
                        $newPageTitle = "Авторизация пользователя - Образовательный курс";
                        break;
                    case 'teacher':
                        $newPageTitle = "Учительская панель - Образовательный курс";
                        break;
                }
            } else {
                echo "<p>Страница не найдена.</p>";
                $newPageTitle = "Страница не найдена - Образовательный курс";
            }
        } else {
            include "pages/home.php";
            $newPageTitle = "Главная страница - Образовательный курс";
        }
    ?>
</div>