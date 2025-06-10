-- База данных: `diplom`
-- Структура таблицы `completed_lecture`
CREATE TABLE `completed_lecture` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `lecture_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
-- Дамп данных таблицы `completed_lecture`
INSERT INTO `completed_lecture` (`id`, `user_id`, `lecture_id`) VALUES
(24, 2, 34);

-- Структура таблицы `completed_test`
--
CREATE TABLE `completed_test` (
  `id` int NOT NULL,
  `id_student` int NOT NULL,
  `id_test` int NOT NULL,
  `assessment` int NOT NULL,
  `try` int NOT NULL DEFAULT '0',
  `date_completed` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы `completed_test`
--

INSERT INTO `completed_test` (`id`, `id_student`, `id_test`, `assessment`, `try`, `date_completed`) VALUES
(4, 2, 2, 5, 3, '2025-06-09'),
(5, 2, 3, 5, 3, '2025-05-30'),
(11, 3, 4, 2, 2, '2025-06-02');

-- Структура таблицы `lecture`
--

CREATE TABLE `lecture` (
  `id` int NOT NULL,
  `id_section` int NOT NULL,
  `title_lecture` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `lecture_text` text NOT NULL,
  `order_lecture` int NOT NULL,
  `test_id` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `lecture`
--

INSERT INTO `lecture` (`id`, `id_section`, `title_lecture`, `lecture_text`, `order_lecture`, `test_id`) VALUES
(1, 1, 'Технические средства информатизации (ТСИ)', '<!-- Заголовок -->', 10, 2),
(9, 2, 'Материнские платы', '<div class=\"\">', 30, 4),
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `id` int NOT NULL,
  `id_test` int NOT NULL,
  `title_questions` text NOT NULL,
  `correct_option` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `wrong_answer1` text NOT NULL,
  `wrong_answer2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `wrong_answer3` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`id`, `id_test`, `title_questions`, `correct_option`, `wrong_answer1`, `wrong_answer2`, `wrong_answer3`) VALUES
(1, 2, 'Что такое ТСИ?', 'Совокупность', 'Технические', 'Программное', 'Средства');
-- Структура таблицы `section`
CREATE TABLE `section` (
  `id` int NOT NULL,
  `title_section` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `order_section` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `section`
--

INSERT INTO `section` (`id`, `title_section`, `order_section`) VALUES
(1, 'Технические средства информатизации (ТСИ) ', 10);
-- Структура таблицы `test`
CREATE TABLE `test` (
  `id` int NOT NULL,
  `title_test` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Дамп данных таблицы `test`
INSERT INTO `test` (`id`, `title_test`) VALUES
(1, ''),
(4, 'Микропроцессоры');
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `group_st` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(8) NOT NULL,
  `progress` int DEFAULT '0',
  `role` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `group_st`, `password`, `progress`, `role`) VALUES
(12, 'Александр', 'Савичев', '-', 'admin_AS', 0, 'admin');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `completed_lecture`
--
ALTER TABLE `completed_lecture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `lecture_id` (`lecture_id`);

--
-- Индексы таблицы `completed_test`
--
ALTER TABLE `completed_test`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `completed_test_fk1` (`id_student`),
  ADD KEY `completed_test_fk2` (`id_test`);

--
-- Индексы таблицы `lecture`
--
ALTER TABLE `lecture`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `lecture_fk1` (`id_section`),
  ADD KEY `lecture_fk2` (`test_id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `questions_fk1` (`id_test`);

--
-- Индексы таблицы `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `completed_lecture`
--
ALTER TABLE `completed_lecture`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `completed_test`
--
ALTER TABLE `completed_test`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `lecture`
--
ALTER TABLE `lecture`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `section`
--
ALTER TABLE `section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `test`
--
ALTER TABLE `test`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `completed_lecture`
--
ALTER TABLE `completed_lecture`
  ADD CONSTRAINT `completed_lecture_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `completed_lecture_ibfk_2` FOREIGN KEY (`lecture_id`) REFERENCES `lecture` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `completed_test`
--
ALTER TABLE `completed_test`
  ADD CONSTRAINT `completed_test_fk1` FOREIGN KEY (`id_student`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `completed_test_fk2` FOREIGN KEY (`id_test`) REFERENCES `test` (`id`);

--
-- Ограничения внешнего ключа таблицы `lecture`
--
ALTER TABLE `lecture`
  ADD CONSTRAINT `lecture_fk1` FOREIGN KEY (`id_section`) RE
  FERENCES `section` (`id`),
  ADD CONSTRAINT `lecture_fk2` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`);

--
-- Ограничения внешнего ключа таблицы `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_fk1` FOREIGN KEY (`id_test`) REFERENCES `test` (`id`);
COMMIT;

