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

// Инициализация: показываем первую лекцию по умолчанию
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
