<div class="main__container">
    <?php 
        // Устанавливаем глобальную переменную для заголовка
        global $newPageTitle;
        if (isset($_GET['page'])) {
            $allowed_pages = ['home', 'course', 'profile', 'authorization'];
            $page = basename($_GET['page']);

            if (in_array($page, $allowed_pages)) {
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