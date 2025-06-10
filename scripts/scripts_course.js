// const sidebarLinks = document.querySelectorAll(".course__accordion-link");
// // Получаем все секции лекций
// const lectures = document.querySelectorAll(".cours__lecture");
// // Функция для загрузки текста лекции через AJAX
// function loadLectureText(lectureId) {
//   const contentElement = document.getElementById(
//     `lecture-content-${lectureId}`
//   );
//   if (!contentElement) {
//     console.error(`Элемент с ID "lecture-content-${lectureId}" не найден.`);
//     return;
//   }

//   // Показываем индикатор загрузки
//   contentElement.innerHTML = "<p>Загрузка...</p>";

//   // Выполняем AJAX-запрос
//   fetch(`actions/action_course-getLecture.php?id=${lectureId}`)
//     .then((response) => response.text()) // Получаем текстовый ответ
//     .then((text) => {
//       // console.log('Ответ сервера:', text); // Отладочная информация
//       try {
//         const data = JSON.parse(text); // Пытаемся разобрать JSON
//         if (data.success) {
//           contentElement.innerHTML =
//             data.text || "<p>Текст лекции отсутствует.</p>";
//         } else {
//           console.error("Ошибка:", data.message);
//           contentElement.innerHTML = `<p>${data.message}</p>`;
//         }
//       } catch (error) {
//         console.error("Ошибка при разборе JSON:", error);
//         contentElement.innerHTML = "<p>Не удалось загрузить текст лекции.</p>";
//       }
//     })
//     .catch((error) => {
//       console.error("Ошибка при загрузке текста лекции:", error);
//       contentElement.innerHTML = "<p>Не удалось загрузить текст лекции.</p>";
//     });
// }

// // Модифицируем функцию showLecture
// function showLecture(targetId) {
//   try {
//     // Скрываем все лекции
//     document.querySelectorAll(".cours__lecture").forEach((lecture) => {
//       lecture.style.display = "none";
//     });

//     // Находим и показываем нужную лекцию
//     const targetLecture = document.getElementById(targetId);
//     if (targetLecture) {
//       targetLecture.style.display = "block";

//       // Загружаем текст лекции
//       const lectureId = targetId.replace("lecture-", "");
//       loadLectureText(lectureId);
//     } else {
//       console.error(`Лекция с ID "${targetId}" не найдена.`);
//     }

//     // Убираем класс active со всех ссылок
//     document.querySelectorAll(".course__accordion-link").forEach((link) => {
//       link.classList.remove("active");
//     });

//     // Добавляем класс active к текущей ссылке
//     const activeLink = document.querySelector(
//       `.course__accordion-link[href="#${targetId}"]`
//     );
//     if (activeLink) {
//       activeLink.classList.add("active");
//     }
//   } catch (error) {
//     console.error("Ошибка при показе лекции:", error);
//   }
// }

// // Назначаем обработчики событий на каждую ссылку в сайдбаре
// sidebarLinks.forEach((link) => {
//   link.addEventListener("click", function (event) {
//     event.preventDefault(); // Предотвращаем стандартное поведение ссылки
//     const targetId = this.getAttribute("href").substring(1); // Получаем ID лекции из href
//     showLecture(targetId); // Показываем соответствующую лекцию
//   });
// });

// // Инициализация: ПОКАЗЫВАЕМ ПЕРВУЮ лекцию по умолчанию
// document.addEventListener("DOMContentLoaded", () => {
//   try {
//     const firstLink = document.querySelector(".course__accordion-link");
//     if (firstLink) {
//       const firstLectureId = firstLink.getAttribute("href").substring(1);
//       showLecture(firstLectureId); // Показываем первую лекцию
//     } else {
//       // console.warn("Нет доступных лекций для отображения.");
//     }
//   } catch (error) {
//     console.error("Ошибка при инициализации:", error);
//   }
// });
// // обработка кнопки "ЗАВЕРШИТЬ лекцию"
// document.addEventListener("DOMContentLoaded", () => {
//   // Проверка авторизации (определи эту переменную где-то глобально)
//   // Функция для показа кнопки "Пройти тест" по ID лекции
//   function showTakeTestButton(lectureId) {
//     const lectureBlock = document.querySelector(`.cours__lecture#lecture-${lectureId}`);
//     const testForm = lectureBlock?.querySelector(".test-form");
//     const footer = lectureBlock?.querySelector(".course__footer");

//     // Удаляем старое содержимое (если уже было)
//     const oldTestButton = footer?.querySelector(".course__take-test");
//     const oldNoTestMessage = footer?.querySelector(".no-test-info");

//     if (oldTestButton) oldTestButton.remove();
//     if (oldNoTestMessage) oldNoTestMessage.remove();

//     // Если тест есть
//     if (testForm) {
//       const takeTestButton = document.createElement("button");
//       takeTestButton.className = "course__take-test";
//       takeTestButton.dataset.lectureId = lectureId;
//       takeTestButton.textContent = "Пройти тест";
//       takeTestButton.style.display = "inline-block";

//       // Вставляем перед кнопкой "Следующая лекция"
//       const nextLectureButton = footer.querySelector(".course__next-lecture");
//       footer.insertBefore(takeTestButton, nextLectureButton);
//     } else {
//       // Теста нет — выводим сообщение
//       const noTestMessage = document.createElement("span");
//       noTestMessage.className = "no-test-info";
//       noTestMessage.textContent = "Для этой лекции отсутствует тест";
//       noTestMessage.style.color = "#888";
//       noTestMessage.style.marginLeft = "10px";

//       // Вставляем перед кнопкой "Следующая лекция"
//       const nextLectureButton = footer.querySelector(".course__next-lecture");
//       footer.insertBefore(noTestMessage, nextLectureButton);
//     }
//   }

//   // Обработчик кнопок "Завершить лекцию"
//   document.querySelectorAll(".course__complete-lecture").forEach((button) => {
//   button.addEventListener("click", function () {
//     if (!isUserAuthorized) {
//       alert("Пожалуйста, авторизуйтесь, чтобы завершить лекцию.");
//       return;
//     }

//     const lectureId = this.dataset.lectureId;

//     fetch("actions/action_complete_lecture.php", {
//       method: "POST",
//       headers: {
//         "Content-Type": "application/x-www-form-urlencoded",
//       },
//       body: `lecture_id=${encodeURIComponent(lectureId)}`,
//     })
//       .then((response) => response.json())
//       .then((data) => {
//         if (data.success) {
//           if (data.isFirstTime) {
//             alert(data.message);
//           }
//           showTakeTestButton(lectureId);
//         } else {
//           console.error("Ошибка:", data.message);
//           alert("Не удалось завершить лекцию.");
//         }
//       })
//       .catch((error) => {
//         console.error("Ошибка сети:", error);
//         alert("Произошла ошибка при завершении лекции.");
//       });
//   });
// });


//   // Обработчик кнопок "Пройти тест"
//   document.querySelectorAll(".course__take-test").forEach((button) => {
//     button.addEventListener("click", function () {
//         const lectureId = this.dataset.lectureId;
//         const lectureBlock = document.querySelector(`.cours__lecture#lecture-${lectureId}`);
//         const testBlock = lectureBlock.querySelector(`.test-block[data-lecture-id="${lectureId}"]`);

//         if (testBlock) {
//             // Очищаем предыдущие ответы
//             const form = testBlock.querySelector(".test-form");
//             if (form) {
//                 const radioButtons = form.querySelectorAll('input[type="radio"]');
//                 radioButtons.forEach(radio => { radio.checked = false; });
//             }

//             // Скрываем результаты
//             const resultsBlock = testBlock.querySelector(".test-results");
//             if (resultsBlock) {
//                 resultsBlock.style.display = "none";
//                 resultsBlock.querySelector(".result-message").textContent = "";
//                 resultsBlock.querySelector(".grade-number").textContent = "";
//             }

//             // Загружаем информацию о попытках
//             loadTestAttempts(lectureId, testBlock);

//             // Показываем тест
//             lectureBlock.querySelector("article").style.display = "none";
//             lectureBlock.querySelector(".course__footer").style.display = "none";
//             // testBlock.style.display = "block";
//         }
//     });
// });
// });

// // обработка кнопок ПРОЙТИ ТЕСТ
// document.addEventListener("DOMContentLoaded", () => {
//   // Находим все кнопки "Пройти тест"
//   const takeTestButtons = document.querySelectorAll(".course__take-test");

//   takeTestButtons.forEach((button) => {
//     button.addEventListener("click", function () {
//       // Получаем ID текущей лекции
//       const lectureId = this.dataset.lectureId;

//       // Находим текущий блок лекции и блок теста для этой лекции
//       const lectureBlock = document.querySelector(
//         `.cours__lecture#lecture-${lectureId}`
//       );
//       const testBlock = lectureBlock.querySelector(
//         `.test-block[data-lecture-id="${lectureId}"]`
//       );

//       if (testBlock) {
//         // Очищаем выбранные ответы в тесте
//         const form = testBlock.querySelector(".test-form");
//         if (form) {
//           const radioButtons = form.querySelectorAll('input[type="radio"]');
//           radioButtons.forEach((radio) => {
//             radio.checked = false; // Сбрасываем выбор
//           });
//         }
//         // Скрываем блок с результатами теста
//         const resultsBlock = testBlock.querySelector(".test-results");
//         if (resultsBlock) {
//           resultsBlock.style.display = "none"; // Скрываем блок результатов
//           resultsBlock.querySelector(".result-message").textContent = ""; // Очищаем текст результата
//           resultsBlock.querySelector(".grade-number").textContent = ""; // Очищаем оценку
//         }
//         // Скрываем основной контент лекции
//         lectureBlock.querySelector("article").style.display = "none";
//         lectureBlock.querySelector(".course__footer").style.display = "none";

//         // Показываем блок теста
//         testBlock.style.display = "block";
//       }
//     });
//   });
//   // Добавляем обработчик для кнопки "Вернуться к лекции"
//   const testBlocks = document.querySelectorAll(".test-block");
//   testBlocks.forEach((testBlock) => {
//     const lectureId = testBlock.dataset.lectureId;
//     const backButton = document.createElement("button");
//     backButton.textContent = "Вернуться к лекции";
//     backButton.classList.add("course__back-to-lecture");
//     backButton.style.marginTop = "20px";

//     // Добавляем кнопку в блок теста
//     testBlock.appendChild(backButton);

//     backButton.addEventListener("click", () => {
//       // Находим родительский блок лекции
//       const lectureBlock = document.querySelector(
//         `.cours__lecture#lecture-${lectureId}`
//       );

//       if (lectureBlock) {
//         // Показываем основной контент лекции
//         lectureBlock.querySelector("article").style.display = "block";
//         lectureBlock.querySelector(".course__footer").style.display = "flex";

//         // Скрываем блок теста
//         testBlock.style.display = "none";
//       }
//     });
//   });
//   // Обработка отправки теста
//   const testForms = document.querySelectorAll(".test-form");
//   testForms.forEach((form) => {
//     form.addEventListener("submit", function (event) {
//       event.preventDefault(); // Предотвращаем отправку формы

//       // Показываем индикатор загрузки
//       const loadingIndicator = form.querySelector(".test-loading-indicator");
//       loadingIndicator.style.display = "block";

//       // Получаем ответы пользователя
//       const formData = new FormData(form);
//       const answers = {};
//       formData.forEach((value, key) => {
//         answers[key] = value;
//       });

//       console.log("Ответы пользователя:", answers);

//       // Имитация отправки данных на сервер (замените на реальный AJAX-запрос)
//       setTimeout(() => {
//         loadingIndicator.style.display = "none";

//         // Показываем результаты теста
//         const resultsBlock = form.nextElementSibling; // Блок с результатами
//         if (resultsBlock && resultsBlock.classList.contains("test-results")) {
//           resultsBlock.style.display = "block";
//           resultsBlock.querySelector(".result-message").textContent =
//             "Тест успешно отправлен!";
//           resultsBlock.querySelector(".grade-number").textContent =
//             "Оценка: 5/5";
//         }

//         alert("Тест успешно отправлен!");
//       }, 1000); // Имитация задержки
//     });
//   });
// });
// // обработка кнопки СЛЕДующая ЛЕКЦИЯ
// document.addEventListener("DOMContentLoaded", () => {
//   // Находим все кнопки "Следующая лекция"
//   const nextLectureButtons = document.querySelectorAll(".course__next-lecture");

//   nextLectureButtons.forEach((button) => {
//     button.addEventListener("click", function () {
//       // Получаем родительскую секцию (текущую лекцию)
//       const currentLecture = this.closest(".cours__lecture");
//       const nextLectureId = currentLecture.dataset.nextLectureId;

//       if (nextLectureId) {
//         // Скрываем текущую лекцию
//         currentLecture.style.display = "none";

//         // Показываем следующую лекцию
//         const nextLecture = document.getElementById(`lecture-${nextLectureId}`);
//         if (nextLecture) {
//           nextLecture.style.display = "block";

//           // Загружаем текст следующей лекции
//           loadLectureText(nextLectureId);
//         }
//       } else {
//         alert("Это последняя лекция.");
//       }
//     });
//   });
// });
// // обработчик событий для кнопок АККАРДИОН
// document.addEventListener("DOMContentLoaded", () => {
//   // Находим все кнопки аккордеона
//   const accordionButtons = document.querySelectorAll(
//     ".course__accordion-section"
//   );

//   accordionButtons.forEach((button) => {
//     button.addEventListener("click", function () {
//       // Находим следующий элемент <ul> с лекциями
//       const lectureList = this.nextElementSibling;

//       // Проверяем текущее состояние
//       const isCollapsed = this.getAttribute("data-state") === "collapsed";

//       if (isCollapsed) {
//         // Разворачиваем список
//         lectureList.style.display = "block";
//         this.setAttribute("data-state", "expanded");
//       } else {
//         // Сворачиваем список
//         lectureList.style.display = "none";
//         this.setAttribute("data-state", "collapsed");
//       }
//     });
//   });
// });
// function showTestResults(form, data) {
//     const resultsBlock = form.nextElementSibling; // Блок с результатами
//     if (!resultsBlock || !resultsBlock.classList.contains("test-results")) return;

//     const resultMessage = resultsBlock.querySelector(".result-message");
//     const gradeNumber = resultsBlock.querySelector(".grade-number");

//     resultsBlock.style.display = "block";

//     if (data.success) {
//         resultMessage.textContent = "Тест успешно пройден!";
//     } else {
//         resultMessage.textContent = "Некоторые ответы неверны.";
//     }

//     gradeNumber.textContent = data.grade;
//     gradeNumber.style.fontSize = "100px";
//     gradeNumber.style.fontWeight = "bold";

//     let color = "green";
//     if (data.grade == 4) color = "blue";
//     else if (data.grade == 3) color = "#cbcb00";
//     else if (data.grade == 2) color = "red";

//     gradeNumber.style.color = color;
// }

// // Функция обновления количества попыток и скрывает всю форму (вопросы и кнопку)
// function updateAttemptsDisplay(lectureId, attemptsLeft) {
//     const testBlock = document.querySelector(`.test-block[data-lecture-id="${lectureId}"]`);
//     if (!testBlock) return;

//     const testForm = testBlock.querySelector(".test-form");
//     const noAttemptsMessage = testBlock.querySelector(".no-attempts-message") || (() => {
//         const el = document.createElement("p");
//         el.className = "no-attempts-message";
//         el.style.color = "red";
//         el.style.display = "none";
//         el.textContent = "Упс! У вас не осталось попыток.";
//         testBlock.appendChild(el);
//         return el;
//     })();

//     const takeTestButton = document.querySelector(`.course__take-test[data-lecture-id="${lectureId}"]`);

//     if (attemptsLeft <= 0) {
//         // Скрываем форму с тестом
//         if (testForm) testForm.style.display = "none";

//         // Показываем сообщение
//         noAttemptsMessage.style.display = "block";

//         // Скрываем кнопку
//         if (takeTestButton) takeTestButton.style.display = "none";
//     } else {
//         // Если есть попытки — показываем форму и скрываем сообщение
//         noAttemptsMessage.style.display = "none";
//         if (testForm) testForm.style.display = "block";
//         if (takeTestButton) takeTestButton.style.display = "inline-block";
//     }

//     // Обновляем счётчик попыток
//     const attemptsSpan = testBlock.querySelector(".attempts-count");
//     if (attemptsSpan) attemptsSpan.textContent = attemptsLeft;

//     const attemptsMessage = testBlock.querySelector(".test-attempts-left");
//     if (attemptsMessage) {
//         attemptsMessage.style.color = attemptsLeft > 0 ? "green" : "red";
//     }
// }
// //обработчик отправки ответов из ТЕСТа на сервер через AJAX.
// document.body.addEventListener("click", function (e) {
//     if (e.target.classList.contains("submit-test")) {
//         const form = e.target.closest(".test-form");
//         if (!form) return;

//         const loadingIndicator = form.querySelector(".test-loading-indicator");

//         // Проверяем, что все вопросы отвечены
//         const questions = form.querySelectorAll(".question-block");
//         let allAnswered = true;
//         questions.forEach((questionBlock) => {
//             const radioButtons = questionBlock.querySelectorAll('input[type="radio"]');
//             const isChecked = Array.from(radioButtons).some(radio => radio.checked);
//             if (!isChecked) allAnswered = false;
//         });

//         if (!allAnswered) {
//             alert("Пожалуйста, ответьте на все вопросы перед отправкой теста.");
//             return;
//         }

//         loadingIndicator.style.display = "block";

//         // Получаем ID лекции
//         const lectureIdInput = form.querySelector("[name='lecture_id']");
//         const lectureId = lectureIdInput ? lectureIdInput.value : form.dataset.lectureId;

//         // Собираем данные формы
//         const formData = new FormData(form);
//         formData.append("lecture_id", lectureId);

//         fetch("actions/action_course-getTest.php", {
//             method: "POST",
//             body: formData
//         })
//         .then(response => response.json())
//         .then(data => {
//             loadingIndicator.style.display = "none";

//             if (data.success) {
//                 alert(`Тест успешно отправлен! Ваша оценка: ${data.grade}`);
//                 updateAttemptsDisplay(lectureId, data.attemptsLeft);
//                 showTestResults(form, data);
//             } else {
//                 alert(data.message || "Ошибка при отправке теста");
//             }
//         })
//         .catch(error => {
//             loadingIndicator.style.display = "none";
//             console.error("Ошибка сети:", error);
//             alert("Произошла ошибка при отправке теста.");
//         });
//     }
// });
// function loadTestAttempts(lectureId, testBlock) {
//     fetch(`actions/action_get_attempts.php?lecture_id=${lectureId}`)
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 updateAttemptsDisplay(lectureId, data.attemptsLeft);
//             } else {              
//                 // он выводит это если лекции не найдено, но так и  есть ее нет, все норм
//                 // console.error("Ошибка загрузки попыток:", data.message);
//                 // console.error("Ошибка загрузки попыток:", lectureId);
//             }
//         })
//         .catch(err => console.error("Ошибка сети:", err))
//         .finally(() => {
//             // Скрываем индикатор загрузки после получения данных
//             const loadingIndicator = testBlock.querySelector(".test-loading-indicator");
//             if (loadingIndicator) loadingIndicator.style.display = "none";
//         });
// }
// document.querySelectorAll(".course__take-test").forEach((button) => {
//     button.addEventListener("click", function () {
//         const lectureId = this.dataset.lectureId;
//         const lectureBlock = document.querySelector(`.cours__lecture#lecture-${lectureId}`);
//         const testBlock = lectureBlock?.querySelector(`.test-block[data-lecture-id="${lectureId}"]`);

//         if (!testBlock) {
//             console.error(`Блок теста для лекции ${lectureId} не найден`);
//             return;
//         }

//         // Находим нужные элементы
//         const form = testBlock.querySelector(".test-form");
//         const resultsBlock = testBlock.querySelector(".test-results");
//         const loadingIndicator = testBlock.querySelector(".test-loading-indicator");
//         const noAttemptsMessage = testBlock.querySelector(".no-attempts-message");

//         // Очищаем предыдущие ответы
//         if (form) {
//             const radioButtons = form.querySelectorAll('input[type="radio"]');
//             radioButtons.forEach(radio => { radio.checked = false; });
//         }

//         // Скрываем результаты
//         if (resultsBlock) {
//             resultsBlock.style.display = "none";
//             const resultMessage = resultsBlock.querySelector(".result-message");
//             const gradeNumber = resultsBlock.querySelector(".grade-number");
//             if (resultMessage) resultMessage.textContent = "";
//             if (gradeNumber) gradeNumber.textContent = "";
//         }

//         // Показываем индикатор загрузки
//         if (loadingIndicator) loadingIndicator.style.display = "block";

//         // Скрываем форму с тестом и сообщение об отсутствии попыток
//         if (form) form.style.display = "none";
//         if (noAttemptsMessage) noAttemptsMessage.style.display = "none";

//         // Показываем блок теста (если был скрыт)
//         testBlock.style.display = "block";

//         // Загружаем информацию о попытках
//         loadTestAttempts(lectureId, testBlock);
//     });
// });
// // управление индикатором загрузки для лекции
// document.querySelectorAll(".course__next-lecture").forEach((button) => {
//   button.addEventListener("click", function () {
//     const lectureSection = this.closest(".course__lecture");
//     if (!lectureSection) {
//       console.error(
//         "Родительский элемент с классом .course__lecture не найден"
//       );
//       return;
//     }

//     const lectureId = lectureSection.id.split("-")[1]; // Получаем ID лекции
//     const loadingIndicator = lectureSection.querySelector(
//       ".lecture-loading-indicator"
//     );
//     const lectureContent = lectureSection.querySelector("p");

//     // Показываем индикатор загрузки
//     loadingIndicator.style.display = "block";
//     lectureContent.style.display = "none";

//     // Имитация загрузки контента лекции
//     setTimeout(() => {
//       // Скрываем индикатор загрузки
//       loadingIndicator.style.display = "none";

//       // Загружаем содержимое лекции (замените на реальный AJAX-запрос)
//       lectureContent.textContent = `Содержимое лекции ${lectureId}`;
//       lectureContent.style.display = "block";
//     }, 1000); // 1 секунда
//   });
// });

// //  добавим проверку и анимацию загрузки:
// document.addEventListener("DOMContentLoaded", function () {
//   // Проверяем, была ли загрузка из кэша
//   if (!isLoadedFromCache) {
//     // Если не из кэша - показываем спиннер на 3 секунды
//     const loadingOverlay = document.createElement("div");
//     loadingOverlay.style.position = "fixed";
//     loadingOverlay.style.top = "0";
//     loadingOverlay.style.left = "0";
//     loadingOverlay.style.width = "100%";
//     loadingOverlay.style.height = "100%";
//     loadingOverlay.style.backgroundColor = "rgba(255, 255, 255, 0.8)";
//     loadingOverlay.style.zIndex = "9999";
//     loadingOverlay.style.display = "flex";
//     loadingOverlay.style.justifyContent = "center";
//     loadingOverlay.style.alignItems = "center";

//     const spinner = document.createElement("div");
//     spinner.classList.add("spinner");

//     loadingOverlay.appendChild(spinner);
//     document.body.appendChild(loadingOverlay);

//     // Автоматически скрываем через 3 секунды
//     setTimeout(() => {
//       document.body.removeChild(loadingOverlay);
//     }, 3000); // 3 секунды
//   }
// });
// // обновление нажатие на меню аккордиона при активном тесте
// document.addEventListener("DOMContentLoaded", () => {
//   // Находим все ссылки аккордеона
//   const sidebarLinks = document.querySelectorAll(".course__accordion-link");

//   // Назначаем обработчики событий на каждую ссылку в сайдбаре
//   sidebarLinks.forEach((link) => {
//     link.addEventListener("click", function (event) {
//       event.preventDefault(); // Предотвращаем стандартное поведение ссылки

//       const targetId = this.getAttribute("href").substring(1); // Получаем ID лекции из href
//       const currentLecture = document.getElementById(targetId);

//       if (currentLecture) {
//         // Проверяем, активна ли текущая лекция
//         const isActive = this.classList.contains("active");

//         if (isActive) {
//           // Если лекция уже активна, проверяем, виден ли блок теста
//           const testBlock = currentLecture.querySelector(".test-block");
//           if (testBlock && testBlock.style.display === "block") {
//             // Скрываем блок теста
//             testBlock.style.display = "none";

//             // Показываем основной контент лекции
//             currentLecture.querySelector("article").style.display = "block";
//             currentLecture.querySelector(".course__footer").style.display =
//               "flex";
//           }
//         } else {
//           // Если лекция не активна, показываем её
//           showLecture(targetId);
//         }
//       }
//     });
//   });

//   // Функция для показа лекции
//   function showLecture(targetId) {
//     try {
//       // Скрываем все лекции
//       document.querySelectorAll(".cours__lecture").forEach((lecture) => {
//         lecture.style.display = "none";
//       });

//       // Находим и показываем нужную лекцию
//       const targetLecture = document.getElementById(targetId);
//       if (targetLecture) {
//         targetLecture.style.display = "block";

//         // Загружаем текст лекции
//         const lectureId = targetId.replace("lecture-", "");
//         loadLectureText(lectureId);
//       } else {
//         console.error(`Лекция с ID "${targetId}" не найдена.`);
//       }

//       // Убираем класс active со всех ссылок
//       document.querySelectorAll(".course__accordion-link").forEach((link) => {
//         link.classList.remove("active");
//       });

//       // Добавляем класс active к текущей ссылке
//       const activeLink = document.querySelector(
//         `.course__accordion-link[href="#${targetId}"]`
//       );
//       if (activeLink) {
//         activeLink.classList.add("active");
//       }
//     } catch (error) {
//       console.error("Ошибка при показе лекции:", error);
//     }
//   }
// });
document.addEventListener("DOMContentLoaded", () => {
  const sidebarLinks = document.querySelectorAll(".course__accordion-link");
  const lectures = document.querySelectorAll(".cours__lecture");

  // === Функция загрузки текста лекции через AJAX ===
  function loadLectureText(lectureId) {
    const contentElement = document.getElementById(`lecture-content-${lectureId}`);
    if (!contentElement) return console.error(`Элемент с ID "lecture-content-${lectureId}" не найден.`);

    contentElement.innerHTML = "<p>Загрузка...</p>";

    fetch(`actions/action_course-getLecture.php?id=${lectureId}`)
      .then((response) => response.text())
      .then((text) => {
        try {
          const data = JSON.parse(text);
          contentElement.innerHTML = data.success ? (data.text || "<p>Текст лекции отсутствует.</p>") :
            `<p>${data.message}</p>`;
        } catch (e) {
          console.error("Ошибка парсинга JSON:", e);
          contentElement.innerHTML = "<p>Не удалось загрузить текст лекции.</p>";
        }
      })
      .catch((error) => {
        console.error("Ошибка загрузки лекции:", error);
        contentElement.innerHTML = "<p>Не удалось загрузить текст лекции.</p>";
      });
  }

  // === Показать лекцию по ID ===
  function showLecture(targetId) {
    try {
      lectures.forEach((l) => (l.style.display = "none"));
      const targetLecture = document.getElementById(targetId);
      if (targetLecture) {
        targetLecture.style.display = "block";
        const lectureId = targetId.replace("lecture-", "");
        loadLectureText(lectureId);
      } else {
        console.error(`Лекция с ID "${targetId}" не найдена.`);
      }

      sidebarLinks.forEach((link) => link.classList.remove("active"));
      const activeLink = document.querySelector(`.course__accordion-link[href="#${targetId}"]`);
      if (activeLink) activeLink.classList.add("active");
    } catch (e) {
      console.error("Ошибка при показе лекции:", e);
    }
  }

  // === Обработчик кликов по ссылкам аккордеона ===
  sidebarLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const targetId = link.getAttribute("href").substring(1);
      const currentLecture = document.getElementById(targetId);
      const isActive = link.classList.contains("active");

      if (isActive && currentLecture) {
        const testBlock = currentLecture.querySelector(".test-block");
        if (testBlock && testBlock.style.display === "block") {
          testBlock.style.display = "none";
          currentLecture.querySelector("article").style.display = "block";
          currentLecture.querySelector(".course__footer").style.display = "flex";
        }
      } else {
        showLecture(targetId);
      }
    });
  });

  // === Показать первую лекцию по умолчанию ===
  const firstLink = document.querySelector(".course__accordion-link");
  if (firstLink) {
    const firstLectureId = firstLink.getAttribute("href").substring(1);
    showLecture(firstLectureId);
  }

  // === Обработчики кнопок "Завершить лекцию" ===
  document.querySelectorAll(".course__complete-lecture").forEach((btn) => {
    btn.addEventListener("click", () => {
      if (!isUserAuthorized) {
        alert("Пожалуйста, авторизуйтесь, чтобы завершить лекцию.");
        return;
      }

      const lectureId = btn.dataset.lectureId;
      fetch("actions/action_complete_lecture.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `lecture_id=${encodeURIComponent(lectureId)}`,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            if (data.isFirstTime) alert(data.message);
            showTakeTestButton(lectureId);
          } else {
            console.error("Ошибка:", data.message);
            alert("Не удалось завершить лекцию.");
          }
        })
        .catch((err) => {
          console.error("Ошибка сети:", err);
          alert("Произошла ошибка при завершении лекции.");
        });
    });
  });

  // === Показывает кнопку "Пройти тест" или сообщение ===
  function showTakeTestButton(lectureId) {
    const lectureBlock = document.querySelector(`.cours__lecture#lecture-${lectureId}`);
    const testForm = lectureBlock?.querySelector(".test-form");
    const footer = lectureBlock?.querySelector(".course__footer");

    const oldTestBtn = footer?.querySelector(".course__take-test");
    const oldNoTestMsg = footer?.querySelector(".no-test-info");
    if (oldTestBtn) oldTestBtn.remove();
    if (oldNoTestMsg) oldNoTestMsg.remove();

    if (testForm) {
      const takeTestBtn = document.createElement("button");
      takeTestBtn.className = "course__take-test";
      takeTestBtn.dataset.lectureId = lectureId;
      takeTestBtn.textContent = "Пройти тест";

      const nextBtn = footer.querySelector(".course__next-lecture");
      footer.insertBefore(takeTestBtn, nextBtn);
    } else {
      const noTestMsg = document.createElement("span");
      noTestMsg.className = "no-test-info";
      noTestMsg.textContent = "Для этой лекции отсутствует тест";
      noTestMsg.style.color = "#888";
      noTestMsg.style.marginLeft = "10px";

      const nextBtn = footer.querySelector(".course__next-lecture");
      footer.insertBefore(noTestMsg, nextBtn);
    }
  }

  // === Кнопка "Следующая лекция" ===
  document.querySelectorAll(".course__next-lecture").forEach((btn) => {
    btn.addEventListener("click", () => {
      const currentLecture = btn.closest(".cours__lecture");
      const nextLectureId = currentLecture.dataset.nextLectureId;

      if (nextLectureId) {
        currentLecture.style.display = "none";
        const nextLecture = document.getElementById(`lecture-${nextLectureId}`);
        if (nextLecture) {
          nextLecture.style.display = "block";
          loadLectureText(nextLectureId);
        }
      } else {
        alert("Это последняя лекция.");
      }
    });
  });

  // === Аккордеон ===
  document.querySelectorAll(".course__accordion-section").forEach((btn) => {
    btn.addEventListener("click", () => {
      const list = btn.nextElementSibling;
      const isCollapsed = btn.getAttribute("data-state") === "collapsed";
      list.style.display = isCollapsed ? "block" : "none";
      btn.setAttribute("data-state", isCollapsed ? "expanded" : "collapsed");
    });
  });

  // === Тестирование ===
  setupTestHandlers();
});

// === Отдельная функция для работы с тестами ===
function setupTestHandlers() {
  // === Обработчик "Пройти тест" ===
  document.querySelectorAll(".course__take-test").forEach((btn) => {
    btn.addEventListener("click", function () {
      const lectureId = this.dataset.lectureId;
      const lectureBlock = document.querySelector(`.cours__lecture#lecture-${lectureId}`);
      const testBlock = lectureBlock.querySelector(`.test-block[data-lecture-id="${lectureId}"]`);

      if (!testBlock) return console.error(`Блок теста для лекции ${lectureId} не найден`);

      const form = testBlock.querySelector(".test-form");
      const resultsBlock = testBlock.querySelector(".test-results");
      const loadingIndicator = testBlock.querySelector(".test-loading-indicator");
      const noAttemptsMessage = testBlock.querySelector(".no-attempts-message");

      if (form) {
        form.querySelectorAll('input[type="radio"]').forEach((r) => (r.checked = false));
      }

      if (resultsBlock) {
        resultsBlock.style.display = "none";
        resultsBlock.querySelector(".result-message").textContent = "";
        resultsBlock.querySelector(".grade-number").textContent = "";
      }

      if (loadingIndicator) loadingIndicator.style.display = "block";
      if (form) form.style.display = "none";
      if (noAttemptsMessage) noAttemptsMessage.style.display = "none";

      testBlock.style.display = "block";
      loadTestAttempts(lectureId, testBlock);
    });
  });

  // === Отправка теста ===
  document.body.addEventListener("click", function (e) {
    if (e.target.classList.contains("submit-test")) {
      const form = e.target.closest(".test-form");
      if (!form) return;

      const questions = form.querySelectorAll(".question-block");
      let allAnswered = true;
      questions.forEach((q) => {
        const radios = q.querySelectorAll('input[type="radio"]');
        allAnswered = Array.from(radios).some((r) => r.checked);
      });

      if (!allAnswered) {
        alert("Пожалуйста, ответьте на все вопросы перед отправкой теста.");
        return;
      }

      const loadingIndicator = form.querySelector(".test-loading-indicator");
      const lectureIdInput = form.querySelector("[name='lecture_id']");
      const lectureId = lectureIdInput ? lectureIdInput.value : form.dataset.lectureId;

      loadingIndicator.style.display = "block";

      const formData = new FormData(form);
      formData.append("lecture_id", lectureId);

      fetch("actions/action_course-getTest.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          loadingIndicator.style.display = "none";
          if (data.success) {
            alert(`Тест успешно отправлен! Ваша оценка: ${data.grade}`);
            updateAttemptsDisplay(lectureId, data.attemptsLeft);
            showTestResults(form, data);
          } else {
            alert(data.message || "Ошибка при отправке теста");
          }
        })
        .catch((err) => {
          loadingIndicator.style.display = "none";
          console.error("Ошибка сети:", err);
          alert("Произошла ошибка при отправке теста.");
        });
    }
  });

  // === Кнопка "Вернуться к лекции" ===
  document.querySelectorAll(".test-block").forEach((testBlock) => {
    const lectureId = testBlock.dataset.lectureId;
    const backButton = document.createElement("button");
    backButton.textContent = "Вернуться к лекции";
    backButton.classList.add("course__back-to-lecture");
    backButton.style.marginTop = "20px";
    testBlock.appendChild(backButton);

    backButton.addEventListener("click", () => {
      const lectureBlock = document.querySelector(`.cours__lecture#lecture-${lectureId}`);
      if (lectureBlock) {
        lectureBlock.querySelector("article").style.display = "block";
        lectureBlock.querySelector(".course__footer").style.display = "flex";
        testBlock.style.display = "none";
      }
    });
  });
}

// === Загрузка попыток ===
function loadTestAttempts(lectureId, testBlock) {
  fetch(`actions/action_get_attempts.php?lecture_id=${lectureId}`)
    .then((res) => res.json())
    .then((data) => {
      if (data.success) updateAttemptsDisplay(lectureId, data.attemptsLeft);
    })
    .catch((err) => console.error("Ошибка сети:", err))
    .finally(() => {
      const loadingIndicator = testBlock.querySelector(".test-loading-indicator");
      if (loadingIndicator) loadingIndicator.style.display = "none";
    });
}

// === Обновление количества попыток ===
function updateAttemptsDisplay(lectureId, attemptsLeft) {
  const testBlock = document.querySelector(`.test-block[data-lecture-id="${lectureId}"]`);
  if (!testBlock) return;

  const testForm = testBlock.querySelector(".test-form");
  const noAttemptsMessage = testBlock.querySelector(".no-attempts-message") || (() => {
    const el = document.createElement("p");
    el.className = "no-attempts-message";
    el.style.color = "red";
    el.style.display = "none";
    el.textContent = "Упс! У вас не осталось попыток.";
    testBlock.appendChild(el);
    return el;
  })();

  const takeTestButton = document.querySelector(`.course__take-test[data-lecture-id="${lectureId}"]`);

  if (attemptsLeft <= 0) {
    if (testForm) testForm.style.display = "none";
    noAttemptsMessage.style.display = "block";
    if (takeTestButton) takeTestButton.style.display = "none";
  } else {
    noAttemptsMessage.style.display = "none";
    if (testForm) testForm.style.display = "block";
    if (takeTestButton) takeTestButton.style.display = "inline-block";
  }

  const attemptsSpan = testBlock.querySelector(".attempts-count");
  if (attemptsSpan) attemptsSpan.textContent = attemptsLeft;

  const attemptsMessage = testBlock.querySelector(".test-attempts-left");
  if (attemptsMessage) attemptsMessage.style.color = attemptsLeft > 0 ? "green" : "red";
}

// === Результаты теста ===
function showTestResults(form, data) {
  const resultsBlock = form.nextElementSibling;
  if (!resultsBlock || !resultsBlock.classList.contains("test-results")) return;

  const resultMessage = resultsBlock.querySelector(".result-message");
  const gradeNumber = resultsBlock.querySelector(".grade-number");

  resultsBlock.style.display = "block";
  resultMessage.textContent = data.success ? "Тест успешно пройден!" : "Некоторые ответы неверны.";
  gradeNumber.textContent = `Оценка: ${data.grade}`;

  let color = "green";
  if (data.grade == 4) color = "blue";
  else if (data.grade == 3) color = "#cbcb00";
  else if (data.grade == 2) color = "red";

  gradeNumber.style.color = color;
  gradeNumber.style.fontSize = "100px";
  gradeNumber.style.fontWeight = "bold";
}
// === ЕДИНЫЙ обработчик для всех кнопок "Пройти тест"
document.body.addEventListener("click", function (e) {
  if (e.target && e.target.classList.contains("course__take-test")) {
    const lectureId = e.target.dataset.lectureId;

    // Находим текущую лекцию и блок теста
    const lectureBlock = document.querySelector(`.cours__lecture#lecture-${lectureId}`);
    const testBlock = lectureBlock?.querySelector(`.test-block[data-lecture-id="${lectureId}"]`);

    if (!testBlock) {
      console.error(`Блок теста для лекции ${lectureId} не найден`);
      return;
    }

    // === Скрываем лекцию ===
    const lectureContent = lectureBlock.querySelector("article");
    const lectureFooter = lectureBlock.querySelector(".course__footer");
    if (lectureContent) lectureContent.style.display = "none";
    if (lectureFooter) lectureFooter.style.display = "none";

    // === Подготавливаем элементы теста ===
    const form = testBlock.querySelector(".test-form");
    const noAttemptsMessage = testBlock.querySelector(".no-attempts-message") || (() => {
      const el = document.createElement("p");
      el.className = "no-attempts-message";
      el.style.color = "red";
      el.style.display = "none";
      el.textContent = "Упс! У вас не осталось попыток.";
      testBlock.appendChild(el);
      return el;
    })();

    const resultsBlock = testBlock.querySelector(".test-results");
    const loadingIndicator = testBlock.querySelector(".test-loading-indicator");

    // === Сбрасываем предыдущие данные ===
    if (form) {
      const radioButtons = form.querySelectorAll('input[type="radio"]');
      radioButtons.forEach(radio => { radio.checked = false; });
    }

    if (resultsBlock) {
      resultsBlock.style.display = "none";
      const resultMessage = resultsBlock.querySelector(".result-message");
      const gradeNumber = resultsBlock.querySelector(".grade-number");
      if (resultMessage) resultMessage.textContent = "";
      if (gradeNumber) gradeNumber.textContent = "";
    }

    // === Показываем индикатор загрузки, скрываем форму и сообщение ===
    if (loadingIndicator) loadingIndicator.style.display = "block";
    if (form) form.style.display = "none";
    noAttemptsMessage.style.display = "none";

    // === Показываем блок теста ===
    testBlock.style.display = "block";

    // === Загружаем информацию о попытках ===
    loadTestAttempts(lectureId, testBlock);
  }
});