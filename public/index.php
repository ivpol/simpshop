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
$router = new Router($_SERVER['REQUEST_URI']);

//дефолтный маршрут, если в корне
if ($router->getRoute() === '/') {
    //$route = $router->getRoute('', '', $router->getParams());
    //$router->setRoute($route);
}

$controllerName = $router->getControllerName();
if ($controllerName) {// TODO добавить проверку на существование класса Controller . $controllerName
    // $controller = new 'Controller' . $controllerName();
    // $action = $router->getAction();
    // if ($action && is_callable([$controller, $action])) {
    //     $controller->$action($router->getParams());
    // } else {
    //
    // }

} else {
    // TODO 404
}
