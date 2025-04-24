<div class="header__container">
    <!-- Логотип (переход на главную страницу) -->
    <div class="header__container-logo">
        <a href="index.php?page=home" class="header__logo">
            <img src="images/logo.png" alt="Логотип сайта" width="40" height="40">
        </a>
    </div>
    <!-- Название "Учебник" (переход на страницу учебника) -->
    <div class="header__container-course">
        <h1 class="header__title">
            <a href="index.php?page=course">Учебник</a>
        </h1>
    </div>
    <!-- Иконка пользователя (переход в личный кабинет) -->
    <div class="header__container-profile">
        <?php
        if (isset($_SESSION['authorization_dostup'])) { ?>
            <a href="index.php?page=profile" class="header__icon">
                <img src="images/icon1.png" alt="Иконка пользователя" width="40" height="40">
            </a>
        <?php } else { ?>
            <a href="#" class="header__icon" id="openAuthModal">
                <img src="images/icon.png" alt="Иконка пользователя" width="40" height="40">
            </a>
        <?php } ?>
    </div>
</div>