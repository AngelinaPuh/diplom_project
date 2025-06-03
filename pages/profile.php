<?php
include_once 'profile_logic.php';
?>
<div class="container">
    <div class="user__div">
        <h2>Личный кабинет</h2>
        <form action="actions/exit.php" method="post">
            <button type="submit" class="logout-btn">Выйти</button>
        </form>
    </div>
    <!-- Блок информации о пользователе -->
    <div class="user-info-block">
        <div class="user-name-group">
            <?= htmlspecialchars($user['surname']) ?> <?= htmlspecialchars($user['name']) ?> -
            <span><?= htmlspecialchars($user['group_st']) ?></span>

        </div>
        <!-- Progress Bar -->
        <div class="progress-bar-container">
            <div class="progress-bar" style="width: <?= htmlspecialchars($progress_percent) ?>%;"></div>
        </div>

        <!-- Текстовый прогресс -->
        <div class="progress-text">
            <?= htmlspecialchars($progress_text) ?>
        </div>
    </div>
    <!-- Блок истории пройденных тестов -->
    <div class="test-history-block">
        <h3>История пройденных тестов</h3>
        <?php if ($tests): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Название теста</th>
                        <th>Оценка</th>
                        <th>Попытка</th>
                        <th>Дата прохождения</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tests as $test): ?>
                        <tr>
                            <td><?= htmlspecialchars($test['title_test']) ?></td>
                            <td><?= htmlspecialchars($test['assessment']) ?></td>
                            <td><?= htmlspecialchars($test['try']) ?></td>
                            <td><?= htmlspecialchars($test['date_completed']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Вы ещё не прошли ни одного теста.</p>
        <?php endif; ?>
    </div>
</div>