<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>BeautySalon</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-content">
            <a href="/salonkrasoti" class="logo">BeautySalon</a>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="services.php">Услуги</a></li>
                    <li><a href="products.php">Продукты</a></li>
                    <li><a href="appointment.php">Записи</a></li>
                    <li><a href="cart.php">Корзина</a></li>
                    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])): ?>
                <li class="profile-menu">
                    <a href="profile.php" class="profile-link">
                        <i class="fas fa-user-circle"></i>
                            <span> <?= htmlspecialchars($_SESSION['user_first_name']) ?></span>
                            </a>
                           
                        </li>
                    <?php else: ?>
                        <li><a href="login.php" class="login-btn">Войти</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>