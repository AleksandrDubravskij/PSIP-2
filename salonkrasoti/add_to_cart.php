<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

$conn = new mysqli("localhost", "root", "Aleksandero1337", "salon_db");
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Проверяем, есть ли уже такой товар у пользователя
$sql = "SELECT * FROM cart_items WHERE user_id = $user_id AND product_id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Уже есть — обновляем количество
    $row = $result->fetch_assoc();
    $newQuantity = $row['quantity'] + 1;
    $updateSql = "UPDATE cart_items SET quantity = $newQuantity WHERE id = " . (int)$row['id'];
    $conn->query($updateSql);
} else {
    // Нет — вставляем новую запись
    $insertSql = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)";
    $conn->query($insertSql);
}

$conn->close();

// Перенаправляем назад на список продуктов или в корзину
header("Location: products.php");
if ($product_id > 0) {
    $sql = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)";
    $conn->query($sql);
} else {
    // Обработка ошибки или сообщение, что product_id не передан
    echo "Неверный идентификатор продукта.";
}

exit;
