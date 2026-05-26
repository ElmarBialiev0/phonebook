<?php
require_once 'Router.php';
require_once 'Controller.php';

$router = new Router();

// Регистрируем маршруты
$router->add('/',              'Controller', 'index');
$router->add('/hello/$name',   'Controller', 'sayHello');
$router->add('/bye/$name',     'Controller', 'sayBye');

// Получаем текущий URL
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Убираем префикс /routing если запускаем из подпапки
$url = str_replace('/routing', '', $url);
if ($url === '') $url = '/';

$router->run($url);
?>