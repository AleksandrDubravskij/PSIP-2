<?php
require 'config.php';
require 'header.php';

$servername = "localhost";
$username = "root";
$password = "Aleksandero1337";
$dbname = "salon_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';

$sql = "SELECT * FROM products WHERE 1";

if (!empty($search)) {
    $sql .= " AND name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

if (!empty($category)) {
    $sql .= " AND category = '" . $conn->real_escape_string($category) . "'";
}

if ($sort == "price_asc") {
    $sql .= " ORDER BY price ASC";
} elseif ($sort == "price_desc") {
    $sql .= " ORDER BY price DESC";
}

$result = $conn->query($sql);
$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Наши продукты</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Пример стилей */
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f8f8;
            text-align: center;
        }
        form {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        input, select, button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        button, .btn {
            background: #ff7eb3;
            color: white;
            border: none;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }
        button:hover, .btn:hover {
            background: #ff5a9a;
        }
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
            max-width: 700px;
            margin: 0 auto;
        }
        .product-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .product-card img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}
        .product-card h3 {
            font-size: 16px;
            margin: 10px 0;
        }
        .product-card p {
            font-size: 14px;
            color: #666;
        }
        .product-card span {
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Наши продукты</h1>

    <!-- Форма поиска и фильтрации -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Поиск продуктов..." value="<?= htmlspecialchars($search) ?>">
        <select name="category">
            <option value="">Все категории</option>
            <option value="Уход за волосами" <?= ($category == 'Уход за волосами') ? 'selected' : '' ?>>Уход за волосами</option>
            <option value="Уход за лицом" <?= ($category == 'Уход за лицом') ? 'selected' : '' ?>>Уход за лицом</option>
            <option value="Уход за телом" <?= ($category == 'Уход за телом') ? 'selected' : '' ?>>Уход за телом</option>
        </select>
        <select name="sort">
            <option value="default">Сортировка</option>
            <option value="price_asc" <?= ($sort == 'price_asc') ? 'selected' : '' ?>>Цена: по возрастанию</option>
            <option value="price_desc" <?= ($sort == 'price_desc') ? 'selected' : '' ?>>Цена: по убыванию</option>
        </select>
        <button type="submit">Применить</button>
    </form>

    <div class="products">
        <?php if (empty($products)): ?>
            <p>Ничего не найдено</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <span><?= htmlspecialchars($product['price']) ?> ₽</span>
                    <!-- Ссылка для добавления товара в корзину -->
                    <a href="add_to_cart.php?product_id=<?= $product['id'] ?>" class="btn">В корзину</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
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
</body>
</html>
