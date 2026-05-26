<?php
class Router {
    private $routes = [];

    public function add($path, $controller, $action) {
        $this->routes[] = [
            'path'       => $path,
            'controller' => $controller,
            'action'     => $action,
        ];
    }

    public function run($url) {
        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\$\w+/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches); // убираем первый элемент (полное совпадение)
                $controller = new $route['controller']();
                call_user_func_array([$controller, $route['action']], $matches);
                return;
            }
        }

        http_response_code(404);
        echo '<h2>404 — страница не найдена</h2>';
    }
}
?>