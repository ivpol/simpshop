<?php

if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', realpath(__DIR__ . '/../'));
}

//Загрузка конфигурационных настроек сайта
include_once ROOT_DIR . '/core/Config.php';
// TODO добавить статику в конфиг
$config = new Config;

// TODO добавить автозагрузку
include_once ROOT_DIR . '/core/Router.php';

//маршрутизация
$route = '';

if ($_SERVER['REQUEST_URI'] != '/') {
    $route = $_SERVER['REQUEST_URI'];
} else {
    //дефолтный маршрут, если в корне
    $route = '/main/index';
}

$router = new Router($route, 'content_type');

$controllerName = $router->getControllerName();
if ($controllerName && class_exists('\\app\\controllers\\' . $controllerName)) {
    $controllerName = '\\app\\controllers\\' . $controllerName;
    $controller = new $controllerName();
    $action = $router->getAction();
    if ($action && is_callable([$controller, $action])) {
         $controller->$action($router->getParams());
    } else {
        // TODO 404
    }

} else {
    // TODO 404
}
