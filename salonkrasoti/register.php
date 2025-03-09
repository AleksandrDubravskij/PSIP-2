<?php
require 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Валидация
    if (empty($firstName)) $errors[] = "Введите имя";
    if (empty($lastName)) $errors[] = "Введите фамилию";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Некорректный email";
    if (strlen($password) < 4) $errors[] = "Пароль должен быть не менее 4 символов";
    if ($password !== $confirmPassword) $errors[] = "Пароли не совпадают";

    // Проверка уникальности email
    $stmt = $pdo->prepare("SELECT email FROM Clients WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) $errors[] = "Email уже занят";

    if (empty($errors)) {
        $rawPassword = $password; 
        
        try {
            $stmt = $pdo->prepare("
            INSERT INTO Clients 
            (first_name, last_name, email, password, registration_date)
            VALUES (?, ?, ?, ?, CURDATE())
        ");
        $stmt->execute([$firstName, $lastName, $email, $rawPassword]);
            header("Location: login.php?success=1");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Ошибка регистрации: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - BeautySalon</title>
   
    <link rel="stylesheet" href="assets/auth.css">

    <style>
        /* Общие стили из предыдущего примера */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #fff5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            color: #333;
            margin-bottom: 1rem;
            text-align: center;
        }

        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            color: #444;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #ff6b6b;
        }

        .divider {
            border-top: 1px solid #eee;
            margin: 2rem 0;
            position: relative;
            text-align: center;
        }

        .divider::after {
            content: "или";
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 0 1rem;
            color: #999;
            font-size: 0.9rem;
        }

        .register-btn {
            width: 100%;
            padding: 14px;
            background: #ff6b6b;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .register-btn:hover {
            background: #ff5252;
        }

        a {
            color: #ff6b6b;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
                margin: 1rem;
            }
        }
    </style>

</head>
<body>
    <div class="login-container">
        <h1>Регистрация</h1>
        
        <?php if(!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Имя</label>
                <input type="text" name="first_name" required 
                    value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Фамилия</label>
                <input type="text" name="last_name" required 
                    value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required 
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Подтвердите пароль</label>
                <input type="password" name="confirm_password" required>
            </div>

            <button type="submit" class="register-btn">Зарегистрироваться</button>
        </form>

        <p class="auth-link">
            Уже есть аккаунт? <a href="login.php">Войдите</a>
        </p>
    </div>
</body>
</html>