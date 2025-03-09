<?php
session_start();
require 'config.php';

// Получаем услуги из БД
try {
    $stmt = $pdo->query("SELECT * FROM Services");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Ошибка загрузки услуг: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Наши услуги</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
    .services-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 20px;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 0;
    }

    .service-card {
        background: #fdfdfd;
        border-radius: 15px;
        padding: 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    .price {
        color: #e673bc;
        font-size: 1.5rem;
        margin: 0.5rem 0;
    }

    .duration {
        color: #888;
        margin-bottom: 0.5rem;
    }

    .book-btn {
        display: inline-block;
        padding: 10px 25px;
        background: #e673bc;
        color: white;
        border-radius: 25px;
        text-decoration: none;
        transition: background 0.3s ease, box-shadow 0.3s ease;
        align-self: center;
        transform: scale(1);
    }

    .book-btn:hover {
        background: #c460a1;
        box-shadow: 0 5px 15px rgba(230,115,188,0.3);
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .services-container {
            padding: 0 10px;
        }

        .service-card {
            padding: 0.5rem;
        }
    }
</style>

</head>
<body>
    <?php require 'header.php'; ?>
    
    <div class="services-container">
        <h1>Наши услуги</h1>
        
        <div class="services-grid">
            <?php foreach ($services as $service): ?>
            <div class="service-card">
                <h2><?= htmlspecialchars($service['service_name']) ?></h2>
                <p><?= htmlspecialchars($service['description']) ?></p>
                <div class="price"><?= $service['price'] ?> ₽</div>
                <div class="duration">Продолжительность: <?= $service['duration'] ?> минут</div>
                <a href="appointment.php?service_id=<?= $service['service_id'] ?>" 
                   class="book-btn">Записаться</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
<br><br><br>
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

</html>