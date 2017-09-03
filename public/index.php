<?php

if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', realpath(__DIR__ . '/../'));
}

//Загрузка конфигурационных настроек сайта
include_once ROOT_DIR . '/core/Config.php';
// TODO добавить статику в конфиг
$config = new Core\Config;

include_once ROOT_DIR . '/core/Autoloader.php';
$autoloader = new Core\Autoloader(ROOT_DIR);
$autoloader->loadFromDir('core/utils');

//маршрутизация
$route = '';

if ($_SERVER['REQUEST_URI'] != '/') {
    $route = $_SERVER['REQUEST_URI'];
} else {
    //дефолтный маршрут, если в корне
    $route = '/main/index';
}

$router = new Core\Router($route, 'content_type');

$controllerName = $router->getControllerName();
if ($controllerName && class_exists('App\\Controllers\\' . $controllerName)) {
    $controllerName = 'App\\Controllers\\' . $controllerName;
    $controller = new $controllerName();
    $action = $router->getAction();
    if ($action && is_callable([$controller, $action])) {
         $controller->$action($router->getParams());
    } else {
        // TODO 404
        echo 'Метод не существует<br>';
    }

} else {
    // TODO 404
    echo 'Класс не существует<br>';
}
