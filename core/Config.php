<?php

namespace Core;

/**
 * Класс для доступа к данным из конфигурационных файлов, доступ к данным объекта как к массиву
 */
class Config implements \ArrayAccess
{
    //массив, в котором хранятся настройки
    private static $_settings;

    //при создании задаются основные настройки
    public function __construct()
    {
        //установка констант
        //TODO ограничить их классом
        if (!defined('ROOT_DIR')) {
            define('ROOT_DIR', realpath(__DIR__ . '/../'));
        }

        self::$_settings = $this->loadSettings('main');
    }

    //функция загрузки данных из конфигурационных файлов, передаётся название (или путь) без расширения
    private function loadSettings($name)
    {
        $settings = [];
        if ($name) {
            $fileName = ROOT_DIR . '/config/' . $name . '.php';
            if (file_exists($fileName) && is_file($fileName)) {
                $settings = include $fileName;
                if (!is_array($settings)) { //данные из файла нужны только если они в формате массива
                    $settings = [];
                }
            }
        }
        return $settings;
    }

    //на попытку установить значение вернётся false
    public function offsetSet($offset, $value)
    {
        return false;
    }

    //проверка на существование элемента массива
    public function offsetExists($offset) {
        return isset(self::$_settings[$offset]);
    }

    //при попытке удалить значение вернётся false
    public function offsetUnset($offset)
    {
        return false;
    }

    // получение данных конфигурации
    public function offsetGet($offset)
    {
        //если ключа нет, загружается файл, имя файла - название ключа
        if (!isset(self::$_settings[$offset])) {
            self::$_settings[$offset] = $this->loadSettings($offset);
        }
        return self::$_settings[$offset];
    }
}
