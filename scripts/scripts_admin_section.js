document.addEventListener("DOMContentLoaded", function () {
  // Кнопки разделов
  const addSectionButton = document.querySelector(".add__section");
  const editSectionButton = document.querySelector(".edit__section");
  const deleteSectionButton = document.querySelector(".delete__section");

  // Формы разделов
  const addSectionForm = document.getElementById("addSectionForm");
  const editSectionSelectForm = document.getElementById(
    "editSectionSelectForm"
  );
  const editSectionForm = document.getElementById("editSectionForm");
  const deleteSectionSelectForm = document.getElementById(
    "deleteSectionSelectForm"
  );

  // Скрыть все формы изначально
  addSectionForm.style.display = "none";
  editSectionSelectForm.style.display = "none";
  editSectionForm.style.display = "none";
  deleteSectionSelectForm.style.display = "none";

  let sectionIdToDelete = null; // Переменная для хранения ID раздела для удаления

  // Обработчики нажатий на кнопки

  addSectionButton.addEventListener("click", function () {
    hideAllForms();
    addSectionForm.style.display = "block";
  });

  editSectionButton.addEventListener("click", function () {
    hideAllForms();
    populateSectionSelect(document.getElementById("sectionSelect")); // Заполняем select перед показом
    editSectionSelectForm.style.display = "block";
  });

  deleteSectionButton.addEventListener("click", function () {
    hideAllForms();
    populateSectionSelect(document.getElementById("deleteSectionSelect")); // Заполняем select перед показом
    deleteSectionSelectForm.style.display = "block";
  });

  // Обработчики отправки форм

  addSectionForm.addEventListener("submit", function (event) {
    event.preventDefault();
    sendSectionData(addSectionForm, "add");
  });

  editSectionSelectForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const selectedSectionId = document.getElementById("sectionSelect").value;
    if (selectedSectionId) {
      //  Получаем данные для выбранного раздела и заполняем форму editSectionForm.

      fetchSectionData(selectedSectionId)
        .then((data) => {
          document.getElementById("editSectionName").value = data.name;
          document.getElementById("editSectionOrder").value = data.order;
          document.getElementById("editSectionId").value = data.id;
          hideAllForms();
          editSectionForm.style.display = "block";
        })
        .catch((error) => {
          console.error("Error fetching section data:", error);
          alert("Ошибка при получении данных раздела.");
        });
    } else {
      alert("Пожалуйста, выберите раздел для редактирования.");
    }
  });

  editSectionForm.addEventListener("submit", function (event) {
    event.preventDefault();
    sendSectionData(editSectionForm, "edit");
  });

  // Переопределяем функцию deleteSection, чтобы сначала проверить наличие лекций
  deleteSectionSelectForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const selectedSectionId = document.getElementById(
      "deleteSectionSelect"
    ).value;
    if (selectedSectionId) {
      sectionIdToDelete = selectedSectionId; // Сохраняем ID раздела
      checkSectionHasLectures(selectedSectionId);
    } else {
      alert("Пожалуйста, выберите раздел для удаления.");
    }
  });

  function checkSectionHasLectures(sectionId) {
    const formData = new FormData();
    formData.append("action", "checkSection");
    formData.append("sectionId", sectionId);

    fetch("../pages/admin_logic/section.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.hasLectures) {
          showLectureActionDialog(sectionId, data.lectureCount);
        } else {
          // Если лекций нет, удаляем раздел
          confirmDeleteSection(sectionId);
        }
      })
      .catch((error) => {
        console.error("Ошибка при проверке наличия лекций:", error);
        alert("Ошибка при проверке наличия лекций: " + error.message);
      });
  }

  function showLectureActionDialog(sectionId, lectureCount) {
    console.log(
      "showLectureActionDialog called with sectionId:",
      sectionId,
      "lectureCount:",
      lectureCount
    );

    // Создаем диалоговое окно (можно использовать модальное окно или просто div)
    const dialog = document.createElement("div");
    console.log("Dialog element created:", dialog);
    dialog.innerHTML = `
        <div class="modal">
          <div class="modal-content">
              <span class="close-button">×</span>
              <p>В этом разделе есть ${lectureCount} лекций. Что вы хотите сделать?</p>
              <button id="deleteLecturesButton">Удалить все лекции из этого раздела</button>
              <button id="moveLecturesButton">Переместить все лекции в другой раздел</button>
              <button id="cancelDeleteButton">Отменить удаление раздела</button>
          </div>
        </div>
    `;

    document.body.appendChild(dialog);
    console.log("Dialog appended to body");
    dialog.classList.add("modal-container");
    console.log("modal-container class added");

    const modal = document.querySelector(".modal");
    console.log("Modal element found:", modal);
    modal.classList.add("show");
    console.log("show class added to modal");

    function closeModal() {
      modal.classList.remove("show");
      dialog.remove();
    }
    document
      .querySelector(".close-button")
      .addEventListener("click", closeModal);

    // Обработчики для кнопок в диалоговом окне
    document
      .getElementById("deleteLecturesButton")
      .addEventListener("click", function () {
        closeModal();
        deleteLectures(sectionId);
      });

    document
      .getElementById("moveLecturesButton")
      .addEventListener("click", function () {
        closeModal();
        showMoveLecturesForm(sectionId);
      });

    document
      .getElementById("cancelDeleteButton")
      .addEventListener("click", function () {
        closeModal();
        sectionIdToDelete = null; // Сбрасываем ID раздела
      });
  }

  function showMoveLecturesForm(sectionId) {
    const formContainer = document.createElement("div");
    formContainer.classList.add("modal-container");
    formContainer.innerHTML = `
            <div class="modal">
              <div class="modal-content">
                  <span class="close-button">×</span>
                  <label for="moveSectionSelect">Выберите раздел для перемещения лекций:</label>
                  <select id="moveSectionSelect" name="moveSectionSelect"></select>
                  <button id="confirmMoveButton" type="button">Переместить лекции</button>
                  <button id="cancelMoveButton" type="button">Отмена</button>
              </div>
            </div>
        `;
    document.body.appendChild(formContainer);

    const modal = document.querySelector(".modal");
    modal.classList.add("show");
    function closeModal() {
      modal.classList.remove("show");
      formContainer.remove();
    }
    document
      .querySelector(".close-button")
      .addEventListener("click", closeModal);

    const selectElement = formContainer.querySelector("#moveSectionSelect");
    populateSectionSelect(selectElement, sectionId); // Исключаем текущий раздел из списка

    formContainer
      .querySelector("#confirmMoveButton")
      .addEventListener("click", function () {
        const newSectionId = selectElement.value;
        if (newSectionId) {
          moveLectures(sectionId, newSectionId);
          closeModal();
        } else {
          alert("Пожалуйста, выберите раздел для перемещения лекций.");
        }
      });

    formContainer
      .querySelector("#cancelMoveButton")
      .addEventListener("click", function () {
        closeModal();
      });
  }

  function populateSectionSelect(selectElement, excludeSectionId = null) {
    // Очищаем select перед заполнением
    selectElement.innerHTML = "";

    // Выполняем запрос к PHP для получения списка разделов
    fetch("../pages/admin_logic/section.php?action=getSections")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((sections) => {
        // Создаем и добавляем опции для каждого раздела
        sections.forEach((section) => {
          if (excludeSectionId === null || section.id != excludeSectionId) {
            const option = document.createElement("option");
            option.value = section.id;
            option.textContent = section.name;
            selectElement.appendChild(option);
          }
        });
      })
      .catch((error) => {
        console.error("Error fetching sections:", error);
        alert("Ошибка при получении списка разделов: " + error.message);
      });
  }

  function deleteLectures(sectionId) {
    const formData = new FormData();
    formData.append("action", "deleteLectures");
    formData.append("sectionId", sectionId);

    fetch("../pages/admin_logic/section.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.text();
      })
      .then((data) => {
        alert(data);
        confirmDeleteSection(sectionId); // После удаления лекций, удаляем раздел
      })
      .catch((error) => {
        console.error("Ошибка при удалении лекций:", error);
        alert("Ошибка при удалении лекций: " + error.message);
      });
  }

  function moveLectures(sectionId, newSectionId) {
    const formData = new FormData();
    formData.append("action", "moveLectures");
    formData.append("sectionId", sectionId);
    formData.append("newSectionId", newSectionId);

    fetch("../pages/admin_logic/section.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.text();
      })
      .then((data) => {
        alert(data);
        confirmDeleteSection(sectionId); // После перемещения лекций, удаляем раздел
      })
      .catch((error) => {
        console.error("Ошибка при перемещении лекций:", error);
        alert("Ошибка при перемещении лекций: " + error.message);
      });
  }

  function confirmDeleteSection(sectionId) {
    const formData = new FormData();
    formData.append("action", "delete");
    formData.append("sectionId", sectionId);

    fetch("../pages/admin_logic/section.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.text();
      })
      .then((data) => {
        alert(data);
        hideAllForms();
        populateSectionSelect(document.getElementById("sectionSelect"));
        populateSectionSelect(document.getElementById("deleteSectionSelect"));
        sectionIdToDelete = null; // Сбрасываем ID раздела
      })
      .catch((error) => {
        console.error("Error deleting section:", error);
        alert("Ошибка при удалении раздела: " + error.message);
      });
  }

  // Вспомогательные функции

  function hideAllForms() {
    addSectionForm.style.display = "none";
    editSectionSelectForm.style.display = "none";
    editSectionForm.style.display = "none";
    deleteSectionSelectForm.style.display = "none";
  }

  function sendSectionData(form, action) {
    const formData = new FormData(form);
    formData.append("action", action); // Добавляем действие (add/edit)

    fetch("../pages/admin_logic/section.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.text(); // Или response.json(), если PHP возвращает JSON
      })
      .then((data) => {
        alert(data); // Выводим сообщение от PHP
        hideAllForms(); // Скрываем форму после отправки
        // Дополнительно: обновить список разделов в select'ах
        populateSectionSelect(document.getElementById("sectionSelect"));
        populateSectionSelect(document.getElementById("deleteSectionSelect"));
      })
      .catch((error) => {
        console.error("There was a problem with the fetch operation:", error);
        alert("Ошибка при отправке данных: " + error.message);
      });
  }

  function fetchSectionData(sectionId) {
    return fetch(
      `../pages/admin_logic/section.php?action=getSection&id=${sectionId}`
    ).then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    });
  }
});
