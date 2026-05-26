<?php
class Controller {
    private function layout(string $title, string $content): string {
        return '<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
    <link rel="stylesheet" href="/routing/style.css">
</head>
<body>
<header>
    <a href="/routing/">Главная</a>
    <a href="/routing/hello/Elmar">Приветствие</a>
    <a href="/routing/bye/Elmar">Прощание</a>
</header>
<main>' . $content . '</main>
<footer></footer>
</body>
</html>';
    }

    public function index() {
        $content = '
        <h2>Добро пожаловать!</h2>
        <p>Это демонстрация роутинга. Доступные маршруты:</p>
        <div class="route-list">
            <a href="/routing/hello/Elmar" class="route-card">
                <span class="route-path">/hello/$name</span>
                <span class="route-desc">Поприветствовать пользователя</span>
            </a>
            <a href="/routing/bye/Elmar" class="route-card">
                <span class="route-path">/bye/$name</span>
                <span class="route-desc">Попрощаться с пользователем</span>
            </a>
        </div>';
        echo $this->layout('Роутинг', $content);
    }

    public function sayHello(string $name) {
        $content = '
        <div class="message-box">
            <h2>Привет, ' . htmlspecialchars($name) . '! 👋</h2>
            <p>Ты попал на маршрут <code>/hello/' . htmlspecialchars($name) . '</code></p>
            <a href="/routing/" class="form-btn">← Назад</a>
        </div>';
        echo $this->layout('Привет', $content);
    }

    public function sayBye(string $name) {
        $content = '
        <div class="message-box">
            <h2>Пока, ' . htmlspecialchars($name) . '! 👋</h2>
            <p>Ты попал на маршрут <code>/bye/' . htmlspecialchars($name) . '</code></p>
            <a href="/routing/" class="form-btn">← Назад</a>
        </div>';
        echo $this->layout('Пока', $content);
    }
}
?>