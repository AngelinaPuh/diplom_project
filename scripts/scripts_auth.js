document.addEventListener("DOMContentLoaded", function () {
    // Элементы
    const authForm = document.getElementById('authorizationForm');
    const openAuthModal = document.getElementById('openAuthModal');
    const authModal = document.getElementById('authModal');
    const modalBackground = document.getElementById('modalBackground');

    // Проверка наличия элементов
    if (!openAuthModal || !authModal || !modalBackground) {
        // console.error("Не все необходимые элементы найдены!");
        return;
    }

    const messageElement_a = document.getElementById('message_a');
    // Открытие модального окна
    openAuthModal.addEventListener('click', function (event) {
        event.preventDefault(); // Предотвращаем переход по ссылке

        // Сохраняем текущий URL в localStorage
        const currentUrl = window.location.href;
        if (currentUrl) {
            localStorage.setItem('previousUrl', currentUrl);
            // console.log("Сохраненный URL:", currentUrl);
        } else {
            console.error("Не удалось получить текущий URL!");
        }

        authModal.classList.add('show'); // Показываем модальное окно
        modalBackground.classList.add('show'); // Показываем фон
    });

    // Закрытие модального окна по кнопке "закрыть"
    // const closeButtons = document.querySelectorAll('.close');
    // closeButtons.forEach(button => {
    //     button.addEventListener('click', function () {
    //         authModal.classList.remove('show'); // Скрываем модальное окно
    //         modalBackground.classList.remove('show'); // Скрываем фон
    //     });
    // });

    // Закрытие модального окна при клике на фон
    modalBackground.addEventListener('click', function () {
        authModal.classList.remove('show');
        modalBackground.classList.remove('show');
    });

    // Закрытие модального окна при клике вне его области
    authModal.addEventListener('click', function (event) {
        if (event.target === authModal) {
            authModal.classList.remove('show');
            modalBackground.classList.remove('show');
        }
    });

    // Переключение между регистрацией и авторизацией
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.querySelector('.auth-container');

    if (signUpButton && signInButton && container) {
        signUpButton.addEventListener('click', () => {
            container.classList.add('right-panel-active');
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove('right-panel-active');
        });
    } else {
        console.error("Элементы для переключения не найдены!");
    }
    authForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(authForm);
        // Отладка: выведем содержимое formData
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        fetch('actions/action_authorization.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Успешная авторизация
                    alert('Авторизация пройдена успешно!');
                    closeModal();
                    window.location.href = '/diplom_project/index.php?page=course'; // или любая другая защищённая страница
                   // // location.reload(); // Перезагрузка страницы для обновления состояния
                } else {
                    // Ошибка авторизации
                    messageElement_a.textContent = data.message;
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                messageElement_a.textContent = 'Произошла ошибка при отправке запроса.';
            });
    });
    console.log("authForm:", authForm);
    // Функция закрытия модального окна
    function closeModal() {
        const authModal = document.getElementById('authModal');
        const modalBackground = document.getElementById('modalBackground');

        if (authModal && modalBackground) {
            authModal.classList.remove('show'); // Скрываем модальное окно
            modalBackground.classList.remove('show'); // Скрываем фоновое затемнение
        } else {
            console.error('Элементы модального окна или фона не найдены!');
        }
    }

});
// регистрация
document.addEventListener('DOMContentLoaded', function () {
    const registrationForm = document.getElementById('registrationForm');
    const messageElement_r = document.getElementById('message_r');

    // Обработка отправки формы регистрации
    registrationForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Предотвращаем стандартную отправку формы

        const formData = new FormData(registrationForm);

        fetch('actions/action_registration.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Успешная регистрация
                    alert(data.message); // Показываем уведомление
                    clearFormFields(registrationForm); // Очищаем поля формы
                } else {
                    // Ошибка регистрации
                    if (messageElement_r) {
                        messageElement_r.textContent = data.message; // Выводим сообщение об ошибке
                    } else {
                        console.error('Элемент для вывода сообщений не найден!');
                    }
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                if (messageElement_r) {
                    messageElement_r.textContent = 'Произошла ошибка при отправке запроса.';
                } else {
                    console.error('Элемент для вывода сообщений не найден!');
                }
            });
    });

    // Функция очистки полей формы
    function clearFormFields(form) {
        if (!form || !(form instanceof HTMLFormElement)) {
            console.error('Передан некорректный элемент формы');
            return;
        }

        // Находим все элементы ввода внутри формы
        const inputs = form.querySelectorAll('input, textarea, select');

        // Очищаем каждое поле
        inputs.forEach(input => {
            if (input.type === 'checkbox' || input.type === 'radio') {
                input.checked = false; // Снимаем выбор для чекбоксов и радиокнопок
            } else if (input.tagName.toLowerCase() === 'select') {
                input.selectedIndex = 0; // Сбрасываем выбор в выпадающем списке
            } else {
                input.value = ''; // Очищаем текстовые поля
            }
        });

        // Очищаем сообщения об ошибках, если они есть
        if (messageElement_r) {
            messageElement_r.textContent = '';
        }
    }
});
