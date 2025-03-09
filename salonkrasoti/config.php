<?php
$host = 'localhost'; // Хост базы данных
$dbname = 'salon_db'; // Имя базы данных
$username = 'root'; // Имя пользователя MySQL (по умолчанию root)
$password = 'Aleksandero1337'; // Пароль MySQL (по умолчанию пустой)

// Создаем подключение
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>