document.addEventListener("DOMContentLoaded", function () {
  const filters = {
    group: "",
    studentOrder: "default",
    test: "",
    grade: "",
    dateOrder: "default",
  };

  let originalRows = [];

  function saveOriginalRows() {
    const rows = document.querySelectorAll("#resultsTable tbody tr");
    originalRows = Array.from(rows).map((row) => row.cloneNode(true));
  }

  function applyFilters() {
    const tbody = document.querySelector("#resultsTable tbody");
    tbody.innerHTML = "";
    originalRows.forEach((row) => tbody.appendChild(row));

    let rows = Array.from(document.querySelectorAll("#resultsTable tbody tr"));

    let filteredRows = rows.filter((row) => {
      const rowGroup = row.getAttribute("data-group");
      const rowTest = row.cells[2].textContent.trim();
      const rowGrade = row.cells[3].textContent.trim();

      return (
        (filters.group === "" || rowGroup === filters.group) &&
        (filters.test === "" || rowTest === filters.test) &&
        (filters.grade === "" || rowGrade === filters.grade)
      );
    });

    // ✅ Сортировка студентов (по алфавиту)
    if (filters.studentOrder !== "default") {
      filteredRows.sort((a, b) => {
        const nameA = a.cells[1].textContent.trim().toLowerCase();
        const nameB = b.cells[1].textContent.trim().toLowerCase();
        return filters.studentOrder === "asc"
          ? nameA.localeCompare(nameB)
          : nameB.localeCompare(nameA);
      });
    }

    // ✅ Сортировка по дате
    if (filters.dateOrder !== "default") {
      filteredRows.sort((a, b) => {
        const dateA = new Date(a.cells[5].textContent.trim());
        const dateB = new Date(b.cells[5].textContent.trim());
        return filters.dateOrder === "newest" ? dateB - dateA : dateA - dateB;
      });
    }

    tbody.innerHTML = "";
    filteredRows.forEach((row) => tbody.appendChild(row));
  }

  function initSelect(id, filterKey) {
    const customSelect = document.getElementById(id);
    const selectedOption = customSelect.querySelector(".selected-option");
    const optionsList = customSelect.querySelector(".select-options");
    const options = customSelect.querySelectorAll(".option");

    optionsList.classList.add("hide");

    customSelect.addEventListener("click", function (e) {
      e.stopPropagation();
      document
        .querySelectorAll(".select-options")
        .forEach((el) => el.classList.add("hide"));
      optionsList.classList.remove("hide");
    });

    options.forEach((option) => {
      option.addEventListener("click", function () {
        const value = this.getAttribute("data-value");
        const text = this.textContent;

        selectedOption.textContent = text;
        filters[filterKey] = value;

        optionsList.classList.add("hide");
        applyFilters();
      });
    });

    document.addEventListener("click", () => optionsList.classList.add("hide"));
  }

  initSelect("groupFilter", "group");
  initSelect("studentFilter", "studentOrder");
  initSelect("testFilter", "test");
  initSelect("gradeFilter", "grade");
  initSelect("dateFilter", "dateOrder");
  const defaultTexts = {
    groupFilter: "Все группы",
    studentFilter: "По умолчанию",
    testFilter: "Тема теста",
    gradeFilter: "Все оценки", // <-- здесь укажи именно тот текст, который в HTML для оценки по умолчанию
    dateFilter: "Дата прохождения",
  };
  const resetBtn = document.getElementById("resetFiltersBtn");
  if (resetBtn) {
    resetBtn.addEventListener("click", function () {
      filters.group = "";
      filters.studentOrder = "default";
      filters.test = "";
      filters.grade = "";
      filters.dateOrder = "default";

      document.querySelectorAll(".custom-select").forEach((select) => {
        const selectedOption = select.querySelector(".selected-option");
        const id = select.id; // например, groupFilter, gradeFilter и т.д.

        if (selectedOption && defaultTexts[id]) {
          selectedOption.textContent = defaultTexts[id];
        }
      });

      applyFilters();
    });
  }

  saveOriginalRows();
  applyFilters();
});
