const sidebarLinks = document.querySelectorAll('.course__accordion-link');
// Получаем все секции лекций
const lectures = document.querySelectorAll('.cours__lecture');
// Функция для загрузки текста лекции через AJAX
function loadLectureText(lectureId) {
    const contentElement = document.getElementById(`lecture-content-${lectureId}`);
    if (!contentElement) {
        console.error(`Элемент с ID "lecture-content-${lectureId}" не найден.`);
        return;
    }

    // Показываем индикатор загрузки
    contentElement.innerHTML = '<p>Загрузка...</p>';
 
    // Выполняем AJAX-запрос 
    fetch(`actions/action_course-getLecture.php?id=${lectureId}`)
    .then(response => response.text()) // Получаем текстовый ответ
    .then(text => {
        // console.log('Ответ сервера:', text); // Отладочная информация
        try {
            const data = JSON.parse(text); // Пытаемся разобрать JSON
            if (data.success) {
                contentElement.innerHTML = data.text || '<p>Текст лекции отсутствует.</p>';
            } else {
                console.error('Ошибка:', data.message);
                contentElement.innerHTML = `<p>${data.message}</p>`;
            }
        } catch (error) {
            console.error('Ошибка при разборе JSON:', error);
            contentElement.innerHTML = '<p>Не удалось загрузить текст лекции.</p>';
        }
    })
    .catch(error => {
        console.error('Ошибка при загрузке текста лекции:', error);
        contentElement.innerHTML = '<p>Не удалось загрузить текст лекции.</p>';
    });
}

// Модифицируем функцию showLecture
function showLecture(targetId) {
    try {
        // Скрываем все лекции
        document.querySelectorAll('.cours__lecture').forEach(lecture => {
            lecture.style.display = 'none';
        });

        // Находим и показываем нужную лекцию
        const targetLecture = document.getElementById(targetId);
        if (targetLecture) {
            targetLecture.style.display = 'block';

            // Загружаем текст лекции
            const lectureId = targetId.replace('lecture-', '');
            loadLectureText(lectureId);
        } else {
            console.error(`Лекция с ID "${targetId}" не найдена.`);
        }

        // Убираем класс active со всех ссылок
        document.querySelectorAll('.course__accordion-link').forEach(link => {
            link.classList.remove('active');
        });

        // Добавляем класс active к текущей ссылке
        const activeLink = document.querySelector(`.course__accordion-link[href="#${targetId}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }
    } catch (error) {
        console.error('Ошибка при показе лекции:', error);
    }
}

// Назначаем обработчики событий на каждую ссылку в сайдбаре
sidebarLinks.forEach(link => {
    link.addEventListener('click', function (event) {
        event.preventDefault(); // Предотвращаем стандартное поведение ссылки
        const targetId = this.getAttribute('href').substring(1); // Получаем ID лекции из href
        showLecture(targetId); // Показываем соответствующую лекцию
    });
});

// Инициализация: ПОКАЗЫВАЕМ ПЕРВУЮ лекцию по умолчанию
document.addEventListener('DOMContentLoaded', () => {
    try {
        const firstLink = document.querySelector('.course__accordion-link');
        if (firstLink) {
            const firstLectureId = firstLink.getAttribute('href').substring(1);
            showLecture(firstLectureId); // Показываем первую лекцию
        } else {
            console.warn('Нет доступных лекций для отображения.');
        }
    } catch (error) {
        console.error('Ошибка при инициализации:', error);
    }
});
// обработка кнопки "ЗАВЕРШИТЬ лекцию"
document.addEventListener('DOMContentLoaded', () => {
    // Находим все кнопки "Завершить лекцию"
    const completeLectureButtons = document.querySelectorAll('.course__complete-lecture');

    completeLectureButtons.forEach(button => {
        button.addEventListener('click', function () {
            if (!isUserAuthorized) {
                alert('Пожалуйста, авторизуйтесь, чтобы завершить лекцию.');
                return;
            }

            // Получаем ID текущей лекции
            const lectureId = this.dataset.lectureId;

            // Находим кнопку "Пройти тест" для этой лекции
            const takeTestButton = document.querySelector(`.course__take-test[data-lecture-id="${lectureId}"]`);
            if (takeTestButton) {
                takeTestButton.style.display = 'inline-block';
            }
        });
    });

    // Находим все кнопки "Пройти тест"
    const takeTestButtons = document.querySelectorAll('.course__take-test');

    takeTestButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Получаем ID текущей лекции
            const lectureId = this.dataset.lectureId;

            // Находим блок с тестом для этой лекции
            const testBlock = document.querySelector(`.test-block[data-lecture-id="${lectureId}"]`);
            if (testBlock) {
                testBlock.style.display = 'block';
            }
        });
    });

    // Обработка отправки теста
    const testForms = document.querySelectorAll('.test-form');
    testForms.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Предотвращаем отправку формы

            // Получаем ответы пользователя
            const question1 = form.querySelector('[name="question1"]').value;
            const question2 = form.querySelector('[name="question2"]').value;

            // Пример обработки ответов
            console.log('Ответ на вопрос 1:', question1);
            console.log('Ответ на вопрос 2:', question2);

            alert('Тест успешно отправлен!');
        });
    });
});
// обработка кнопок ПРОЙТИ ТЕСТ
document.addEventListener('DOMContentLoaded', () => {
    // Находим все кнопки "Пройти тест"
    const takeTestButtons = document.querySelectorAll('.course__take-test');

    takeTestButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Получаем ID текущей лекции
            const lectureId = this.dataset.lectureId;

            // Находим блок с тестом для этой лекции
            const testBlock = document.querySelector(`.test-block[data-lecture-id="${lectureId}"]`);
            if (testBlock) {
                testBlock.style.display = 'block';
            }
        });
    });

    // Обработка отправки теста
    const testForms = document.querySelectorAll('.test-form');
    testForms.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Предотвращаем отправку формы

            // Получаем ответы пользователя
            const question1 = form.querySelector('[name="question1"]').value;
            const question2 = form.querySelector('[name="question2"]').value;

            // Пример обработки ответов
            console.log('Ответ на вопрос 1:', question1);
            console.log('Ответ на вопрос 2:', question2);

            alert('Тест успешно отправлен!');
        });
    });
});
// обработка кнопки СЛЕДующая ЛЕКЦИЯ
document.addEventListener('DOMContentLoaded', () => {
    // Находим все кнопки "Следующая лекция"
    const nextLectureButtons = document.querySelectorAll('.course__next-lecture');

    nextLectureButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Получаем родительскую секцию (текущую лекцию)
            const currentLecture = this.closest('.cours__lecture');
            const nextLectureId = currentLecture.dataset.nextLectureId;

            if (nextLectureId) {
                // Скрываем текущую лекцию
                currentLecture.style.display = 'none';

                // Показываем следующую лекцию
                const nextLecture = document.getElementById(`lecture-${nextLectureId}`);
                if (nextLecture) {
                    nextLecture.style.display = 'block';

                    // Загружаем текст следующей лекции
                    loadLectureText(nextLectureId);
                }
            } else {
                alert('Это последняя лекция.');
            }
        });
    });
});
// обработчик событий для кнопок АККАРДИОН
document.addEventListener('DOMContentLoaded', () => {
    // Находим все кнопки аккордеона
    const accordionButtons = document.querySelectorAll('.course__accordion-section');

    accordionButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Находим следующий элемент <ul> с лекциями
            const lectureList = this.nextElementSibling;

            // Проверяем текущее состояние
            const isCollapsed = this.getAttribute('data-state') === 'collapsed';

            if (isCollapsed) {
                // Разворачиваем список
                lectureList.style.display = 'block';
                this.setAttribute('data-state', 'expanded');
            } else {
                // Сворачиваем список
                lectureList.style.display = 'none';
                this.setAttribute('data-state', 'collapsed');
            }
        });
    });
});

//обработчик отправки ответов из ТЕСТа на сервер через AJAX.
document.addEventListener('DOMContentLoaded', function () {
    // Обработка клика по кнопке "Отправить тест"
    document.body.addEventListener('click', function (e) {
        if (e.target.classList.contains('submit-test')) {
            const form = e.target.closest('.test-form');
            if (!form) return;

            // Проверяем, что все вопросы отвечены
            const questions = form.querySelectorAll('.question-block');
            let allAnswered = true;

            questions.forEach(questionBlock => {
                const radioButtons = questionBlock.querySelectorAll('input[type="radio"]');
                const isChecked = Array.from(radioButtons).some(radio => radio.checked);
                if (!isChecked) {
                    allAnswered = false;
                }
            });

            if (!allAnswered) {
                alert('Пожалуйста, ответьте на все вопросы перед отправкой теста.');
                return; // Прерываем выполнение, если не все вопросы отвечены
            }

            // Если все вопросы отвечены, отправляем данные на сервер
            const formData = new FormData(form);
            const resultsBlock = form.nextElementSibling; // Блок для вывода результатов

            fetch('actions/action_course-getTest.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Показываем блок с результатами
                resultsBlock.style.display = 'block';

                // Выводим сообщение о результатах
                const resultMessage = resultsBlock.querySelector('.result-message');
                const gradeNumber = resultsBlock.querySelector('.grade-number');

                if (data.success) {
                    resultMessage.textContent = 'Тест пройден успешно!';
                } else {
                    resultMessage.textContent = 'Некоторые ответы неверны.';
                }

                // Выводим оценку
                const correctPercentage = data.correctPercentage;
                let grade = '';
                let color = '';
                if (correctPercentage === 100) {
                    grade = '5';
                    color = 'green';
                } else if (correctPercentage >= 75) {
                    grade = '4';
                    color = 'blue';
                } else if (correctPercentage >= 50) {
                    grade = '3';
                    color = '#cbcb00';
                } else {
                    grade = '2';
                    color = 'red';
                }
                // Отображаем оценку с цветом
                gradeNumber.textContent = grade;
                gradeNumber.style.fontSize = '100px';
                gradeNumber.style.color = color;
                gradeNumber.style.fontWeight = 'bold';
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });
        }
    });
});