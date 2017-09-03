<?php
namespace Core;

/**
 *
 */
class Router
{
    private $route; //оригинальный адрес

    private $mask; //маска для разбивки адреса на переменные (разделители - . и /)

    private $controller; //массив с названием текущего контроллера, его методом и параметрами

    //при создании задаётся маршрут и маска
    public function __construct($route = '/', $mask = '')
    {
        $this->controller = ['name' => '', 'action' => '', 'params' => []];
        $this->setRoute($route, $mask);

    }

    //установка маршрута
    public function setRoute($route, $mask = '')
    {
        $route = trim(strtolower($route));
        if ($route) {
            $this->route = $route;
        } else {
            $route = '/';
        }
        $this->mask = trim($mask);
        $this->setController();//задаются актуальные данные о контроллере
    }

    //установка данных контроллера
    private function setController()
    {
        $controller = [];
        $routeData = preg_split('/\//', $this->route, 2, PREG_SPLIT_NO_EMPTY); //строка запроса разбивается на две, по разделителю, пустые не нужны
        if (isset($routeData[0]) && $this->isNameCorrect($routeData[0])) { //если первая подстрока есть и не содержит некорректных символов
            $controller['name'] = $routeData[0]; //первая подстрока - название контроллера
            if (isset($routeData[1])) { //если есть вторая подстрока, она должна содержать название метода и параметры
                $paramsPosition = strpos($routeData[1], '?'); //позиция начала строки с параметрами
                if ($paramsPosition !== 0) { // если строка с данными пути не начинается с параметров, а содержит как минимум название метода
                    $paramsString = '';//параметры для метода контроллера, которые переданы в формате get
                    if ($paramsPosition > 0) { // если строка с параметрами вообще есть
                        $routeData = explode('?', $routeData[1], 2); //данные о пути теперь содержат строку с названием метода (и, если есть, параметрами для маски) и строку с параметрами
                        $paramsString = $routeData[1];
                    } else { //если строки с параметрами нет
                        $routeData = [$routeData[1]];//данные о пути содержат только одну строку - с названием метода и, если есть, парметрами для маски
                    }
                    // данные о пути теперь массив с двумя строками
                    // (первая - название метода котроллера, вторая - строка параметров, которые передаются через маску)
                    $routeData = preg_split('/[\/\.]/', $routeData[0], 2);
                    if ($routeData[0] && $this->isNameCorrect($routeData[0])) { //если название контроллера корректно
                        $controller['action'] = $routeData[0];
                        $params = [];//общий массив, в который добавляются все параметры
                        if ($paramsString) {//если есть параметры в формате get-запроса
                            parse_str($paramsString, $params);
                        }
                        // TODO добавить расширенный функционал для создания масок адресов (напр. *.par)
                        if ($this->mask) {// если есть маска
                            $key = '';
                            $value = '';
                            //получаем полный массив, где названия переменных разбиты разделителями (если пустой - соответствующее значение из строки запроса не нужно)
                            $paramsKeys = preg_split('/[\/\.]/', $this->mask);
                            //массив из строки, разбитый по разделителям
                            $paramsValues = [];
                            if (!empty($routeData[1])) {
                                $paramsValues = preg_split('/[\/\.]/', $routeData[1]);
                            }
                            while ($key = array_shift($paramsKeys)) {
                                if ($key) {//если непустое название ключа, то ему задаётся соответствующее значение из строки
                                    if (isset($paramsValues[0])) {
                                        $value = array_shift($paramsValues);
                                    } else {
                                        $value = '';
                                    }
                                    $params[$key] = $value;
                                }
                            }
                        }
                        $controller['params'] = $params;
                        $this->controller = $controller;
                    }
                }
            }
        }
    }


    //возвращает строку с названием контроллера, который запрашивается
    public function getControllerName()
    {
        return $this->controller['name'];
    }

    //возвращает строку с названиме запрашиваемого метода
    public function getAction()
    {
        return $this->controller['action'];
    }

    //возвращает преобразованную в массив строку запроса после ?
    public function getParams()
    {
        return $this->controller['params'];
    }

    //возвращает строку, сгенерированную из названий контроллера и метода и массива параметров
    public function getRoute($controller = '', $action = '', Array $params = [])
    {

    }

    //преобразование массива в строку запроса
    private function getParamsString(Array $params = [])
    {
        if ($params) {
            return http_build_query($params);
        }
    }


    //проверка названий контроллеров и методов на корректность
    private function isNameCorrect($name)
    {
        if ($name) {
            if (preg_match('/[^a-z]/i', $name)) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

}
