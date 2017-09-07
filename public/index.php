<?php

$rootDir = realpath(__DIR__ . '/../');

include_once $rootDir . '/Core/Autoloader.php';
$autoloader = new Core\Autoloader($rootDir);
$autoloader->loadFromDir('Core/utils');

$urlParams = $_GET;

if (empty($urlParams['_module_'])) {
    $urlParams['_module_'] = 'main';
}

$router = new Core\Router($urlParams);

$controllerName = $router->getControllerName();
if ($controllerName && class_exists('App\\Controllers\\' . $controllerName)) {
    $controllerName = 'App\\Controllers\\' . $controllerName;
    $controller = new $controllerName();
    $method = $router->getMethod();
    if (is_callable([$controller, $method])) {
         $controller->$method($router->getParams());
    }
} else {
    // TODO 404
    echo 'Класс не существует<br>';
}
