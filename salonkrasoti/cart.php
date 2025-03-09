<?php
session_start();
require 'header.php';
// Если пользователь не авторизован, отправляем на логин (пример)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Подключаемся к базе
$conn = new mysqli("localhost", "root", "Aleksandero1337", "salon_db");
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Если нажали “Очистить корзину” (передаём action=clear в URL)
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    $sql = "DELETE FROM cart_items WHERE user_id = " . (int)$user_id;
    $conn->query($sql);
    header("Location: cart.php");
    exit;
}

// Получаем все товары из корзины текущего пользователя
$sql = "
    SELECT cart_items.id AS cart_id,
           cart_items.quantity,
           products.id AS product_id,
           products.name,
           products.category,
           products.price,
           products.image 
    FROM cart_items
    JOIN products ON cart_items.product_id = products.id
    WHERE cart_items.user_id = $user_id
";
$result = $conn->query($sql);

$items = [];
$total = 0;
$count = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
        $total += $row['price'] * $row['quantity']; // Суммируем
        $count += $row['quantity']; // Общее кол-во
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/style.css">
    <title>Корзина</title>
    <style>/* Подключаем красивый шрифт */
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');

body {
    font-family: 'Montserrat', sans-serif;
    background: #f9f9f9;
   
}

.cart-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

h1 {
    margin-bottom: 20px;
    color: #333;
    text-align: center;
}

.cart-content {
    display: flex;
    gap: 20px;
}

/* Левая часть (товары) */
.cart-items {
    flex: 2;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.cart-item {
    display: flex;
    gap: 15px;
    border-bottom: 1px solid #eee;
    padding: 10px 0;
    align-items: center;
}

.cart-item img {
    width: 100px;
    height: auto;
    border-radius: 8px;
    object-fit: cover;
}

.cart-item-info {
    flex: 1;
}

.cart-item-info h3 {
    margin: 0 0 5px;
    font-size: 18px;
    color: #333;
}

.cart-item-info p {
    margin: 5px 0;
    color: #555;
}

.cart-item-actions {
    display: flex;
    align-items: center;
}

.cart-item-actions a {
    color: #ff5a9a;
    text-decoration: none;
    font-weight: bold;
    padding: 20px
}

/* Правая часть (итоговая сумма) */
.cart-summary {
    flex: 1;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: left;
    min-width: 200px;
}

.cart-summary h2 {
    margin-top: 0;
    color: #333;
}

.cart-summary p {
    margin: 5px 0;
    color: #555;
}

.cart-summary h3 {
    margin: 10px 0;
    color: #ff5a9a;
}

/* Кнопки */
.btn {
    display: inline-block;
    padding: 10px 15px;
    margin-top: 10px;
    border-radius: 5px;
    text-decoration: none;
    color: #fff;
    background: #ff7eb3;
    font-size: 16px;
    font-weight: bold;
    transition: background 0.3s ease;
}

.btn:hover {
    background: #ff5a9a;
}

.btn-delete {
    color: red;
}

.btn-delete:hover {
    color: darkred;
}
</style>
</head>
<body>

<div class="cart-container">
    <h1>Корзина</h1>
    <div class="cart-content">
        <!-- Левая колонка: товары -->
        <div class="cart-items">
            <?php if (empty($items)): ?>
                <p>Ваша корзина пуста</p>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <div class="cart-item">
                        <!-- Динамический вывод картинки -->
                        <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>">

                        <div class="cart-item-info">
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <p><?= htmlspecialchars($item['category']) ?></p>
                            <p><?= $item['price'] ?> ₽ x <?= $item['quantity'] ?></p>
                        </div>
                        <div class="cart-item-actions">
                              <a href="update_cart.php?cart_id=<?= $item['cart_id'] ?>&action=add">+</a>
                            <!-- Ссылка для удаления 1 позиции из корзины -->
                            <a href="remove_from_cart.php?id=<?= $item['cart_id'] ?>" class="btn-delete">Удалить</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

        <!-- Правая колонка: сумма, скидка, доставка -->
        <div class="cart-summary">
            <h2>Сумма заказа</h2>
            <p>Товаров (<?= $count ?>)</p>
            <p>Скидка: 0 ₽</p>
            <p>Доставка: Бесплатно</p>
            <h3>Итого: <?= $total ?> ₽</h3>

            <!-- Очистить корзину (все товары) -->
            <a href="cart.php?action=clear" class="btn">Очистить корзину</a>

            <!-- Оформить заказ -->
            <a href="products.php" class="btn">Оформить заказ</a>
        </div>
    </div>
</div>

<br><br><br><br>><br><br>><br>
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
</body>
</html>
