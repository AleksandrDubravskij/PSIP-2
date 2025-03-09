<?php
session_start();
require 'config.php';

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Получение всех услуг
$services = $pdo->query("SELECT * FROM Services")->fetchAll();

// Обработка выбора услуги
$selected_service = null;
if (isset($_GET['service_id'])) {
    $service_id = (int)$_GET['service_id'];
    $stmt = $pdo->prepare("SELECT * FROM Services WHERE service_id = ?");
    $stmt->execute([$service_id]);
    $selected_service = $stmt->fetch();
}

// Получение мастеров для выбранной услуги
$employees = [];
if ($selected_service) {
    $stmt = $pdo->prepare("
        SELECT e.*
        FROM Employees e
        JOIN Employee_Services es ON e.employee_id = es.employee_id
        WHERE es.service_id = ?
    ");
    $stmt->execute([$service_id]);
    $employees = $stmt->fetchAll();
}


    // Обработка формы записи
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        // Валидация данных
        $required = ['service_id', 'employee_id', 'datetime'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Все поля обязательны для заполнения");
            }
        }

        $service_id = (int)$_POST['service_id'];
        $employee_id = (int)$_POST['employee_id'];
        $datetime = date('Y-m-d H:i:s', strtotime($_POST['datetime']));

        // Проверка доступности мастера
        $check = $pdo->prepare("
            SELECT * FROM Appointments 
            WHERE employee_id = :employee_id 
            AND appointment_date = :datetime
            AND status != 'canceled'
        ");
        $check->execute([
            ':employee_id' => $employee_id,
            ':datetime' => $datetime
        ]);

        if ($check->rowCount() > 0) {
            throw new Exception("Это время уже занято");
        }

        // Создание записи
        $stmt = $pdo->prepare("
            INSERT INTO Appointments 
            (client_id, employee_id, service_id, appointment_date, status)
            VALUES (:client_id, :employee_id, :service_id, :datetime, 'Подтвержденно')
        ");

        $stmt->execute([
            ':client_id' => $_SESSION['user_id'],
            ':employee_id' => $employee_id,
            ':service_id' => $service_id,
            ':datetime' => $datetime
        ]);

        header("Location: profile.php?success=1");
        exit();
    }

require 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Запись на услугу</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
    .container {
        max-width: 500px;
        margin: 35px 0px;
        background: #fdfdfd;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s ease;
        align-items: left  ;
    }

    .container:hover {
        box-shadow: 0 7px 30px rgba(0,0,0,0.15);
    }

    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 2rem;
        font-size: 2.2em;
    }

    .form-group {
        margin-bottom: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #444;
        font-weight: 600;
    }

    select, input[type="datetime-local"] {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #eee;
        border-radius: 8px;
        background: #f8f8f8;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    select, 
    input[type="datetime-local"] {
        width: 60%;
        margin: 0;
    }

    select:focus, 
    input[type="datetime-local"]:focus {
        border-color: #e673bc;
        background: #fff;
        outline: none;
        box-shadow: 0 0 8px rgba(230,115,188,0.2);
    }

    .flatpickr.flatpickr-input {
        width: 56%;
        padding: 12px 15px;
        border: 2px solid #eee;
        border-radius: 8px;
        background: #f8f8f8;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .flatpickr.flatpickr-input:focus {
        border-color: #e673bc;
        background: #fff;
        outline: none;
        box-shadow: 0 0 8px rgba(230,115,188,0.2);
    }

    button[type="submit"] {
        width: 60%;
        padding: 15px;
        background: #e673bc;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 1rem;
        transform: scale(1);
    }

    button[type="submit"]:hover {
        background: #c460a1;
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(230,115,188,0.3);
    }

    .error {
        color: red;
        font-size: 0.9em;
    }

    @media (max-width: 768px) {
        .container {
            margin: 1rem;
            padding: 1.5rem;
        }
        
        h1 {
            font-size: 1.8em;
        }

        select, 
        input[type="datetime-local"],
        button[type="submit"] {
            width: 100%;
        }
    }
</style>

</head>
<body>
<div class="container">
    <form method="get" action="">
        <label for="service">Выберите услугу:</label>
        <select id="service" name="service_id" onchange="this.form.submit()">
            <option value="">-- Выберите услугу --</option>
            <?php foreach ($services as $service): ?>
                <option value="<?= htmlspecialchars($service['service_id']) ?>" <?= isset($_GET['service_id']) && $_GET['service_id'] == $service['service_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($service['service_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>
        <form method="POST">
            <!-- Скрытое поле с service_id -->
            <input type="hidden" name="service_id" value="<?= $service_id ?>">
            
            <!-- Выбор даты и времени -->
            <div class="form-group">
                <label>Дата и время:</label>
                <input type="datetime-local" name="datetime" required 
                    class="flatpickr" data-enable-time="true">
            </div>

            <!-- Выбор мастера -->
            <div class="form-group">
                <label>Мастер:</label>
                <?php if (!empty($employees)): ?>
                    <select name="employee_id" required>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= $employee['employee_id'] ?>">
                                <?= htmlspecialchars($employee['first_name']) ?> 
                                <?= htmlspecialchars($employee['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <p class="error">Нет доступных мастеров для этой услуги</p>
                <?php endif; ?>
            </div>

            <button type="submit" name="submit">Записаться</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr('.flatpickr', {
            minDate: "today",
            dateFormat: "Y-m-d H:i",
            time_24hr: true
        });
    </script>
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