<?php

if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', realpath(__DIR__ . '/../'));
}

//Загрузка конфигурационных настроек сайта
$configClass = ROOT_DIR . '/core/Config.php';
if (file_exists($configClass) && is_file($configClass)) {
    include_once $configClass;
    $config = new Config;
}

// если удалось получить конфигурацию
if (!empty($config)) {

}
