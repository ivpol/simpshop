<?php

/**
 *
 */
class Router
{
    private $route; //маршрут к методу контроллера

    private $controller; //массив с названием текущего контроллера и его методом

    private static $aliases; //алиасы для адресов страниц

    //при создании задаётся маршрут
    public function __construct($route = '/', Array $aliases = [])
    {
        //по умолчанию маршрут - корень сайта
        if (!$route) {
            $route = '/';
        }

        $this->setRoute($route);
        $this->setAliases($aliases);

    }

    //установка маршрута
    public function setRoute($route)
    {
        $this->route = trim(strtolower($route)); //задаётся отформатированный маршрут
        $this->setController($this->route);//задаются актуальные данные о контроллере
    }

    //установка данных контроллера
    private function setController($route)
    {
        //удаляется корень для удобства
        if (strpos($route, '/') === 0) {
            $route = substr($route, 1);
        }

        $this->controller = ['name' => '', 'action' => '', 'params' => []]; //удаляется информация о текущем контроллере

        //если кроме корня в маршруте есть ещё данные
        if ($route) {
            $queryStart = strpos($route, '?');//есть ли в строке запроса get-параметры
            if ($queryStart !== false) {
                parse_str(substr($route, $queryStart + 1), $params);
                $this->controller['params'] = $params; //строка запроса преобразуется в массив и добавляется параметром контроллера
                $route = strstr($route, '?', true);
            }

            $routeSegments = explode('/', $route, 3); //массив сегментов из строки маршрута, нужны только первые два

            //установка актуальных данных о контроллере

            //первый сегмент - название класса контроллера, проверяется на корректность
            if (isset($routeSegments[0]) && $this->checkNameFormat($routeSegments[0])) {
                $this->controller['name'] = $routeSegments[0];
            }

            //второй сегмент - названиме метода контроллера, если есть название контроллера и прошло проверку на корректность
            if ($this->controller['name'] && isset($routeSegments[1]) && $this->checkNameFormat($routeSegments[1])) {
                $this->controller['action'] = $routeSegments[1];
            }
        }
    }

    //установка алиасов для адресов страниц
    public function setAliases(Array $aliases)
    {
        if ($aliases && empty($this->aliases)) {
            $aliases = array_map($aliases, 'strtolower');//форматирование всех алиасов
            $this->aliases = $aliases;
            $aliasRoute = $this->findAlias($this->route);//поиск текущего маршрута в списке
            if ($aliasRoute) {
                $this->setController($aliasRoute);//запрос заменяется на соответствующую строку запроса из алиасов
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
        $route = '/';//по умолчанию - корень сайта

        if (!$controller || !$this->checkNameFormat($controller)) {
            $controller = $this->controller['name'];//если не указан контроллер, используется текущий
        }

        if ($controller) {
            $route .= '/' . $controller;
            if (!$action || !$this->checkNameFormat($action)) {
                $action = $this->controller['action'];//если не указан метод - используется текущий
            }
            if ($action) {
                $route .= '/' . $action;
            }
            if ($params) {
                $route .= '?' . $this->getParamsString();//массив параметров форматируется в строку get-запроса
            }
            $aliasRoute = $this->findAlias($route);
            if ($aliasRoute) {
                $route = $aliasRoute;
            }
        }
        return $route;
    }

    //преобразование массива в строку запроса
    private function getParamsString(Array $params = [])
    {
        if ($params) {
            return http_build_query($params);
        }
    }

    //поиск в алиасах маршрута
    private function findAlias($route)
    {
        if ($this->aliases && $route && !empty($this->aliases[$route])) {
            return $this->aliases[$route];
        } else {
            return false;
        }
    }

    //проверка названий контроллеров и методов на корректность
    private function checkNameFormat($name)
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
