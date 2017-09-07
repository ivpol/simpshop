<?php

namespace Core;

/**
 *
 */
class Router
{
    private $urlParams;

    private $controller;

    public function __construct(Array $urlParams = [])
    {
        $this->controller = ['name' => '', 'method' => '', 'params' => []];
        if ($urlParams) {
            $this->setRoute($urlParams);
        }
    }

    private function setRoute(Array $urlParams)
    {
        if (!empty($urlParams['_module_'])) {
            $this->controller['name'] = $urlParams['_module_'];

        }
        if (!empty($urlParams['_action_'])) {
            $this->controller['method'] = $urlParams['_action_'];
        }
        if (!empty($urlParams['_params_'])) {
            $params = explode('/', $urlParams['_params_']);
            array_walk($params, function (&$param) {
                if (strpos($param, '.') !== false) {
                    $param = explode('.', $param);
                }
            });
            $this->controller['params'] = $params;
        }
        unset($urlParams['_module_'], $urlParams['_params_'], $urlParams['_action_']);
        if (!empty($urlParams)) {
            $this->controller['params'] += $urlParams;
        }
    }

    public function getControllerName()
    {
        return $this->controller['name'];
    }

    public function getMethod()
    {
        return $this->controller['method'];
    }

    public function getParams()
    {
        return $this->controller['params'];
    }

    public function getRoute($controller = '', $action = '', Array $params = [])
    {

    }

    private function getParamsString(Array $params = [])
    {
        if ($params) {
            return http_build_query($params);
        }
    }

}
