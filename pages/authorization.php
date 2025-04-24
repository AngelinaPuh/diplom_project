<!-- Фоновое затемнение -->
<div id="modalBackground" class="modal-background"></div>

<!-- Модальное окно авторизации -->
<div id="authModal" class="modal hidden">
    <div class="modal-content auth-container">
        <span class="close">&times;</span>
        <!-- Авторизация -->
        <div class="form-container sign-in-container">
            <form action="actions/action_authorization.php" id="authorizationForm" method="POST">
                <h1>Войти в аккаунт</h1>
                <input type="text" placeholder="Фамилия" name="surname" required minlength="2" maxlength="50" title="Только буквы, длина от 2 до 50 символов" autocomplete="off"/>
                <input type="password" placeholder="Пароль" name="password" required minlength="6" maxlength="15" title="Пароль должен содержать минимум 6 символов максимум 15" autocomplete="off"/>
                <button type="submit" name="button_authorization">Войти</button>
                <p id="message_a"></p> <!-- Добавлено для отображения сообщений -->
            </form>
        </div>

        <!-- Регистрация -->
        <div class="form-container sign-up-container">
            <form action="actions/action_registration.php" id="registrationForm" method="POST">
                <h1>Создать аккаунт</h1>
                <input type="text" placeholder="Фамилия" name="surname" required minlength="2" maxlength="50" title="Только буквы, длина от 2 до 50 символов" autocomplete="off"/>
                <input type="text" placeholder="Имя" name="name" required minlength="2" maxlength="50" title="Только буквы, длина от 2 до 50 символов" autocomplete="off"/>
                <input type="text" placeholder="Группа" name="group" required minlength="3" maxlength="4" title="Только буквы и цифры, длина 3 или 4 символа" autocomplete="off"/>
                <input type="password" placeholder="Пароль" name="password" required minlength="6" maxlength="15" title="Пароль должен содержать минимум 6 символов максимум 15" autocomplete="off"/>
                <input type="password" placeholder="Повторите пароль" name="password_check" required minlength="6" maxlength="15" title="Пароль должен совпадать с предыдущим полем" autocomplete="off"/>
                <button type="submit" name="button_registration">Зарегистрироваться</button>
                <p id="message_r"></p> <!-- Элемент для вывода сообщений -->
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <!-- Зарегистрироваться -->
                <div class="overlay-panel overlay-left">
                    <h1 class="white">Добро пожаловать!</h1>
                    <p class="white">Пожалуйста, войдите в аккаунт</p>
                    <button class="overlay-button" id="signIn">Войти</button>
                </div>
                <!-- Авторизоваться -->
                <div class="overlay-panel overlay-right">
                    <h1 class="white">Вы еще не с нами?</h1>
                    <p class="white">Скорее регистрируйтесь!</p>
                    <button class="overlay-button" id="signUp">Зарегистрироваться</button>
                </div>
            </div>
        </div>
    </div>
</div>
