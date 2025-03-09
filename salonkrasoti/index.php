<?php
require 'header.php';

?>

    
    <!-- Основной контент страницы -->
    <section class="hero">
    <div class="hero-content">
        <h1 class="hero-title">Добро пожаловать в Beautysalon</h1>
        <p class="hero-subtitle">Профессиональный уход за вашей красотой. Мы предлагаем широкий спектр услуг для волос, лица и тела.</p>
        <div class="hero-buttons">
            <a href="services.php" class="btn primary">Наши услуги</a>
            <a href="appointment.php" class="btn secondary">Записаться</a>
        </div>
    </div>
</section> 
    

    <section class="advantages">
    <div class="container">
        <h2 class="section-title">Наши преимущества</h2>
        
        <div class="advantages-grid">
            <!-- Карточка 1 -->
            <div class="advantage-card">
                <div class="advantage-icon">
                    <svg aria-hidden="true">"..."</svg>
                </div>
                <h3 class="advantage-title">Профессиональные мастера</h3>
                <p class="advantage-text">Наши специалисты имеют многолетний опыт работы и регулярно повышают свою квалификацию.</p>
            </div>

            <!-- Карточка 2 -->
            <div class="advantage-card">
                <div class="advantage-icon">
                    <svg aria-hidden="true">...</svg>
                </div>
                <h3 class="advantage-title">Качественная косметика</h3>
                <p class="advantage-text">Мы используем только профессиональную косметику ведущих мировых брендов.</p>
            </div>

            <!-- Карточка 3 -->
            <div class="advantage-card">
                <div class="advantage-icon">
                    <svg aria-hidden="true">...</svg>
                </div>
                <h3 class="advantage-title">Удобная запись</h3>
                <p class="advantage-text">Запишитесь онлайн в любое удобное для вас время без звонков и ожидания.</p>
            </div>
        </div>
    </div>
</section>
<section class="services">
    <div class="container">
        <h2 class="section-title">Популярные услуги</h2>
        
        <div class="services-grid">
            <!-- Карточка 1 -->
            <div class="service-card">
                <div class="service-image">
                    <img src="img/Fon.jpg" alt="Стрижка и укладка">
                </div>
                <div class="service-content">
                    <h3>Стрижка и укладка</h3>
                    <p>Профессиональная стрижка и укладка волос.</p>
                    <div class="service-price">2500 ₽</div>
                    <button class="service-btn">Записаться</button>
                </div>
            </div>

            <!-- Карточка 2 -->
            <div class="service-card">
                <div class="service-image">
                    <img src="img/servis.png" alt="Окрашивание волос">
                </div>
                <div class="service-content">
                    <h3>Окрашивание волос</h3>
                    <p>Окрашивание волос с использованием профессиональных красителей.</p>
                    <div class="service-price">4500 ₽</div>
                    <button class="service-btn">Записаться</button>
                </div>
            </div>

            <!-- Карточка 3 -->
            <div class="service-card">
                <div class="service-image">
                    <img src="img/servis1.png" alt="Маникюр и педикюр">
                </div>
                <div class="service-content">
                    <h3>Маникюр и педикюр</h3>
                    <p>Комплексный уход за ногтями рук и ног с покрытием гель-лаком.</p>
                    <div class="service-price">3000 ₽</div>
                    <button class="service-btn">Записаться</button>
                </div>
            </div>
        </div>

        <div class="all-services">
            <a href="services.php" class="all-services-link">Все услуги →</a>
        </div>
    </div>
</section>
<section class="reviews-section">
    <div class="container">
        <!-- Блок отзывов -->
        <h2 class="section-title">Отзывы клиентов</h2>
        
        <div class="reviews-grid">
            <!-- Отзыв 1 -->
            <div class="review-card">
                <div class="review-header">
                    <h3 class="review-author">Анна К.</h3>
                    <div class="review-stars">★★★★★</div>
                </div>
                <p class="review-text">Отличный салон! Мастер Елена сделала мне потрясающую стрижку и окрашивание. Очень довольна результатом!</p>
            </div>

            <!-- Отзыв 2 -->
            <div class="review-card">
                <div class="review-header">
                    <h3 class="review-author">Мария Д.</h3>
                    <div class="review-stars">★★★★★</div>
                </div>
                <p class="review-text">Регулярно делаю маникюр в этом салоне. Всегда качественно и аккуратно. Рекомендую!</p>
            </div>

            <!-- Отзыв 3 -->
            <div class="review-card">
                <div class="review-header">
                    <h3 class="review-author">Сергей П.</h3>
                    <div class="review-stars">★★★★★</div>
                </div>
                <p class="review-text">Отличный сервис и приятная атмосфера. Мастера знают свое дело. Буду приходить еще!</p>
            </div>
        </div>

        <!-- CTA Блок -->
        <div class="cta-block">
            <h2 class="cta-title">Готовы преобразиться?</h2>
            <p class="cta-text">Запишитесь на консультацию прямо сейчас и получите скидку 10% на первое посещение.</p>
            <a href="appointment.php" class="cta-button">Записаться сейчас</a>
        </div>
    </div>
</section>
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
</div>

