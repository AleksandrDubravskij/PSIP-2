<?php

require 'config.php';
require 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM Clients WHERE client_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Удаление записи и связанных данных
if (isset($_GET['delete_appointment_id'])) {
    $delete_appointment_id = (int)$_GET['delete_appointment_id'];

    // Удаляем связанные записи из таблицы Payments
    $stmt = $pdo->prepare("DELETE FROM Payments WHERE appointment_id = ?");
    $stmt->execute([$delete_appointment_id]);

    // Удаляем запись из таблицы Appointments
    $stmt = $pdo->prepare("DELETE FROM Appointments WHERE appointment_id = ?");
    $stmt->execute([$delete_appointment_id]);

    header("Location: profile.php"); // Перенаправление для обновления страницы
    exit();
}

// Получаем записи пользователя
$appointments = $pdo->prepare("
    SELECT a.*, s.service_name, e.first_name, e.last_name 
    FROM Appointments a
    JOIN Services s ON a.service_id = s.service_id
    JOIN Employees e ON a.employee_id = e.employee_id
    WHERE a.client_id = ?
");
$appointments->execute([$_SESSION['user_id']]);
?>

<div class="profile-container">
    <h1>Профиль пользователя</h1>
    <div class="profile-info">
        <p><strong>Имя:</strong> <?= htmlspecialchars($user['first_name']) ?></p>
        <p><strong>Фамилия:</strong> <?= htmlspecialchars($user['last_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Дата регистрации:</strong> <?= $user['registration_date'] ?></p>
    </div>
    <a href="logout.php" class="logout-btn">Выйти</a>
    <br>
</div>
<div class="profile-container">
    <h1>Ваши записи</h1>
    
    <?php if ($appointments->rowCount() > 0): ?>
    <div class="appointments-list">
        <?php foreach ($appointments as $appointment): ?>
        <div class="appointment-card">
            <h3><?= htmlspecialchars($appointment['service_name']) ?></h3>
            <p>Мастер: <?= htmlspecialchars($appointment['first_name']) ?> <?= htmlspecialchars($appointment['last_name']) ?></p>
            <p>Дата: <?= date('d.m.Y H:i', strtotime($appointment['appointment_date'])) ?></p>
            <p>Статус: <?= htmlspecialchars($appointment['status']) ?></p>
            <a href="?delete_appointment_id=<?= $appointment['appointment_id'] ?>" class="delete-btn">Удалить</a>

        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p>У вас пока нет активных записей.</p>
    <?php endif; ?>
<style>
    body {
    background-color:rgb(255, 255, 255); /* Светло-серый фон */
}
    .delete-btn {
    background-color: #ff4d4d; /* Красный фон */
    border: none; /* Без границы */
    color: white; /* Белый текст */
    padding: 10px 20px; /* Отступы */
    text-align: center; /* Текст по центру */
    text-decoration: none; /* Без подчеркивания */
    display: inline-block; /* Инлайн-блок */
    font-size: 16px; /* Размер шрифта */
    margin: 4px 2px; /* Поля */
    cursor: pointer; /* Курсор указателя */
    border-radius: 5px; /* Закругленные углы */
    transition: background-color 0.3s ease; /* Плавный переход фона */
}

.delete-btn:hover {
    background-color: #e60000; /* Темно-красный фон при наведении */
}
</style>
</div>
<br><br><br><br>
<footer class="footer">
    <div class="footer-container">
        <!-- Основной контент подвала -->
        <div class="footer-columns">
            <!-- Лого и описание -->
            <div class="footer-column">
                <h2 class="footer-logo">BeautySalon</h2>
                <p class="footer-description">
                    Профессиональный салон красоты с широким спектром услуг 
                    для ваших волос, лица и тела.
                </p>
            </div>

            <!-- Контакты -->
            <div class="footer-column">
                <h3 class="footer-title">Контакты</h3>
                <ul class="footer-contacts">
                    <li>Адрес: ул. Примерная, 123</li>
                    <li>Телефон: <a href="tel:+71234567890">+7 (123) 456-7890</a></li>
                    <li>Email: <a href="mailto:info@beautysalon.ru">info@beautysalon.ru</a></li>
                </ul>
            </div>

            <!-- Часы работы -->
            <div class="footer-column">
                <h3 class="footer-title">Часы работы</h3>
                <ul class="footer-hours">
                    <li>Пн-Пт: 9:00 - 20:00</li>
                    <li>Сб: 10:00 - 18:00</li>
                    <li>Вс: Выходной</li>
                </ul>
            </div>
        </div>

        <!-- Копирайт -->
        <div class="footer-bottom">
            <p class="copyright">
                © 2025 BeautySalon. Все права защищены.
            </p>
        </div>
    </div>
</footer>