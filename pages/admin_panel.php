<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Панель управления контентом</title>
  <link rel="stylesheet" href="../style/style_admin.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $("#clearCacheButton").click(function() {
        $.ajax({
          url: 'admin_logic/clear_cache.php', // Путь к вашему скрипту
          type: 'POST', // Или GET, если хотите
          dataType: 'json', // Ожидаем JSON в ответе
          success: function(response) {
            if (response.success) {
              alert(response.message); // Выводим сообщение об успехе
            } else {
              alert("Ошибка: " + response.message); // Выводим сообщение об ошибке
            }
          },
          error: function(xhr, status, error) {
            alert("Произошла ошибка при выполнении запроса: " + error); // Обрабатываем ошибки AJAX
          }
        });
      });
    });
  </script>
</head>

<body>

  <div class="back-button-container">
    <button class="back-btn" onclick="window.history.back()">← Назад</button>
  </div>

  <h1 class="panel-title">Панель управления контентом</h1>

  <button id="clearCacheButton" class="action-btn">Очистить кэш</button>
  <!-- Формы будут отображаться здесь -->
  <div id="formContainer"></div>

  <div class="table-container">
    <div class="table-row">

      <!-- Разделы -->
      <div class="table-cell">
        <h2>1. Разделы</h2>
        <div class="table-buttons">
          <button class="action-btn add__section">Добавить</button>
          <button class="action-btn edit__section">Редактировать</button>
          <button class="action-btn delete__section">Удалить</button>
        </div>
      </div>

      <!-- Лекции -->
      <div class="table-cell">
        <h2>2. Лекции</h2>
        <div class="table-buttons">
          <button class="action-btn add__lecture">Добавить</button>
          <button class="action-btn edit__lecture">Редактировать</button>
          <button class="action-btn delete__lecture">Удалить</button>
        </div>
      </div>

      <!-- Тесты -->
      <div class="table-cell">
        <h2>3. Тесты</h2>
        <div class="table-buttons">
          <button class="action-btn add__test">Добавить</button>
          <button class="action-btn edit__test">Редактировать</button>
          <button class="action-btn delete__test">Удалить</button>
        </div>
      </div>

    </div>
  </div>
  <!-- ========== РАЗДЕЛ ========== -->
  <!-- Форма добавления раздела -->
  <div id="addSectionForm" class="form-container">
    <h2>Добавить раздел</h2>
    <label for="sectionName">Название раздела:</label>
    <input type="text" id="sectionName" name="sectionName">

    <label for="sectionOrder">Порядковый номер раздела:</label>
    <input type="number" id="sectionOrder" name="sectionOrder">

    <button>Сохранить</button>
  </div>

  <!-- Форма выбора раздела для редактирования -->
  <div id="editSectionSelectForm" class="form-container">
    <h2>Редактировать раздел</h2>
    <label for="sectionSelect">Выберите раздел для редактирования:</label>
    <select id="sectionSelect" name="sectionSelect">
      <!-- Здесь будут опции разделов (пока пусто) -->
    </select>
    <button id="editSectionButton" class="edit">Редактировать</button>
  </div>

  <!-- Форма редактирования раздела -->
  <div id="editSectionForm" class="form-container">
    <h2>Редактировать раздел</h2>
    <label for="editSectionName">Название раздела:</label>
    <input type="text" id="editSectionName" name="editSectionName">

    <label for="editSectionOrder">Порядковый номер раздела:</label>
    <input type="number" id="editSectionOrder" name="editSectionOrder">

    <button>Сохранить изменения</button>
  </div>

  <!-- Форма выбора раздела для удаления -->
  <div id="deleteSectionSelectForm" class="form-container">
    <h2>Удалить раздел</h2>
    <label for="deleteSectionSelect">Выберите раздел для удаления:</label>
    <select id="deleteSectionSelect" name="deleteSectionSelect">
      <!-- Здесь будут опции разделов (пока пусто) -->
    </select>
    <button id="deleteSectionButton" class="del">Удалить</button>
  </div>

  <!-- ========== ЛЕКЦИИ ========== -->
  <!-- Форма добавления лекции -->
  <div id="addLectureForm" class="form-container">
    <h2>Добавить лекцию</h2>

    <label for="lectureSection">Раздел:</label>
    <select id="lectureSection" name="lectureSection">
      <!-- Здесь будут опции разделов (заполнять динамически) -->
    </select>

    <label for="lectureName">Название лекции:</label>
    <input type="text" id="lectureName" name="lectureName">

    <label for="lectureText">Текст лекции:</label>
    <textarea id="lectureText" name="lectureText" rows="5" cols="50"></textarea>

    <label for="lectureOrder">Порядковый номер лекции:</label>
    <input type="number" id="lectureOrder" name="lectureOrder">

    <label for="lectureTest">Тест:</label>
    <select id="lectureTest" name="lectureTest">
      <!-- Здесь будут опции тестов (заполнять динамически) -->
    </select>

    <button>Сохранить</button>
  </div>

  <!-- Форма выбора лекции для редактирования -->
  <div id="editLectureSelectForm" class="form-container">
    <h2>Редактировать лекцию</h2>

    <label for="lectureSelect">Выберите лекцию для редактирования:</label>
    <select id="lectureSelect" name="lectureSelect">
      <!-- Здесь будут опции лекций (заполнять динамически) -->
    </select>

    <button id="editLectureButton"  class="edit">Редактировать</button>
  </div>

  <!-- Форма редактирования лекции -->
  <div id="editLectureForm" class="form-container">
    <h2>Редактировать лекцию</h2>

    <label for="editLectureSection">Раздел:</label>
    <select id="editLectureSection" name="editLectureSection">
      <!-- Здесь будут опции разделов (заполнять динамически) -->
    </select>

    <label for="editLectureName">Название лекции:</label>
    <input type="text" id="editLectureName" name="editLectureName">

    <label for="editLectureText">Текст лекции:</label>
    <textarea id="editLectureText" name="editLectureText" rows="5" cols="50"></textarea>

    <label for="editLectureOrder">Порядковый номер лекции:</label>
    <input type="number" id="editLectureOrder" name="editLectureOrder">

    <label for="editLectureTest">Тест:</label>
    <select id="editLectureTest" name="editLectureTest">
      <!-- Здесь будут опции тестов (заполнять динамически) -->
    </select>

    <button>Сохранить изменения</button>
  </div>

  <!-- Форма выбора лекции для удаления  -->
  <div id="deleteLectureSelectForm" class="form-container">
    <h2>Удалить лекцию</h2>
    <label for="deleteLectureSelect">Выберите лекцию для удаления:</label>
    <select id="deleteLectureSelect" name="deleteLectureSelect">
      <!-- Здесь будут опции лекций (заполнять динамически) -->
    </select>
    <button id="deleteLectureButton" class="del">Удалить</button>
  </div>


  <!-- ========== ТЕСТЫ ========== -->
  <!-- Форма добавления теста -->
  <div id="addTestForm" class="form-container">
    <h2>Добавить тест</h2>
    <label for="testName">Название теста:</label>
    <input type="text" id="testName" name="testName">
    <button id="saveTestButton">Сохранить тест</button>
  </div>

  <!-- Форма добавления вопросов (скрыта изначально)style="display: none;" -->
  <div id="addQuestionForm" class="form-container">
    <h2>Добавить вопросы</h2>
    <div id="questionsContainer">
      <!-- Здесь будут динамически добавляться формы вопросов -->
      <div class="question-block">
        <h3>Вопрос 1</h3>
        <label for="questionText1">Название вопроса:</label>
        <input type="text" id="questionText1" name="questionText[]">

        <label for="correctAnswer1">Правильный вариант:</label>
        <input type="text" id="correctAnswer1" name="correctAnswer[]">

        <label for="incorrectAnswer1_1">Неправильный вариант 1:</label>
        <input type="text" id="incorrectAnswer1_1" name="incorrectAnswer1[]">

        <label for="incorrectAnswer1_2">Неправильный вариант 2:</label>
        <input type="text" id="incorrectAnswer1_2" name="incorrectAnswer2[]">

        <label for="incorrectAnswer1_3">Неправильный вариант 3:</label>
        <input type="text" id="incorrectAnswer1_3" name="incorrectAnswer3[]">

        <button class="save-question-button">Сохранить вопрос</button>
        <button class="delete-question-button del">Удалить вопрос</button>
      </div>
    </div>
    <button id="addAnotherQuestionButton" class="add__q">Добавить еще один вопрос</button>
  </div>

  <!-- Форма редактирования теста -->
  <div id="editTestSelectForm" class="form-container">
    <h2>Редактировать тест</h2>
    <label for="testSelect">Выберите тест для редактирования:</label>
    <select id="testSelect" name="testSelect">
      <!-- Здесь будут опции тестов (заполнять динамически) -->
    </select>
    <button id="editTestButton"  class="edit">Редактировать</button>
  </div>

  <!-- Форма редактирования названия теста и вопросов (скрыта изначально) style="display: none;-->
  <div id="editTestForm" class="form-container">
    <h2>Редактировать тест</h2>
    <label for="editTestName">Название теста:</label>
    <input type="text" id="editTestName" name="editTestName">

    <div id="editQuestionsContainer">
      <!-- Здесь будут динамически добавляться формы редактирования вопросов -->
      <div class="question-block">
        <h3>Вопрос 1</h3>
        <label for="editQuestionText1">Название вопроса:</label>
        <input type="text" id="editQuestionText1" name="editQuestionText[]">

        <label for="editCorrectAnswer1">Правильный вариант:</label>
        <input type="text" id="editCorrectAnswer1" name="editCorrectAnswer[]">

        <label for="editIncorrectAnswer1_1">Неправильный вариант 1:</label>
        <input type="text" id="editIncorrectAnswer1_1" name="editIncorrectAnswer1[]">

        <label for="editIncorrectAnswer1_2">Неправильный вариант 2:</label>
        <input type="text" id="editIncorrectAnswer1_2" name="editIncorrectAnswer2[]">

        <label for="editIncorrectAnswer1_3">Неправильный вариант 3:</label>
        <input type="text" id="editIncorrectAnswer1_3" name="editIncorrectAnswer3[]">

        <button class="save-question-button">Сохранить вопрос</button>
        <button class="delete-question-button del">Удалить вопрос</button>
      </div>
    </div>
    <button id="saveChangesButton">Сохранить изменения</button>
  </div>

  <!-- Форма удаления теста -->
  <div id="deleteTestSelectForm" class="form-container">
    <h2>Удалить тест</h2>
    <label for="deleteTestSelect">Выберите тест для удаления:</label>
    <select id="deleteTestSelect" name="deleteTestSelect">
      <!-- Здесь будут опции тестов (заполнять динамически) -->
    </select>
    <button id="deleteTestButton" class="del">Удалить</button>
  </div>

  <script src="../scripts/scripts_admin.js"></script>
</body>

</html>