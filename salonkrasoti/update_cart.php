<?php
session_start();

// Проверяем авторизацию пользователя
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

$cart_id = isset($_GET['cart_id']) ? (int)$_GET['cart_id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($cart_id <= 0 || ($action !== 'add' && $action !== 'subtract')) {
    header("Location: cart.php");
    exit;
}

$conn = new mysqli("localhost", "root", "Aleksandero1337", "salon_db");
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Проверяем, что данный элемент корзины принадлежит текущему пользователю
$sql = "SELECT quantity FROM cart_items WHERE id = $cart_id AND user_id = $user_id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_quantity = $row['quantity'];

    if ($action === 'add') {
        $new_quantity = $current_quantity + 1;
        $updateSql = "UPDATE cart_items SET quantity = $new_quantity WHERE id = $cart_id";
        $conn->query($updateSql);
    } elseif ($action === 'subtract') {
        // Если количество больше 1, уменьшаем, иначе удаляем элемент из корзины
        if ($current_quantity > 1) {
            $new_quantity = $current_quantity - 1;
            $updateSql = "UPDATE cart_items SET quantity = $new_quantity WHERE id = $cart_id";
            $conn->query($updateSql);
        } else {
            $deleteSql = "DELETE FROM cart_items WHERE id = $cart_id";
            $conn->query($deleteSql);
        }
    }
}

$conn->close();
header("Location: cart.php");
exit;
?>
