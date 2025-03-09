<?php
session_start();
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

$cart_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Удаляем запись из cart_items только для текущего пользователя
$sql = "DELETE FROM cart_items WHERE id = $cart_id AND user_id = $user_id";
$conn->query($sql);

$conn->close();

// Возвращаемся в корзину
header("Location: cart.php");
exit;
