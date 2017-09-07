<?php

namespace Core;

/**
 *
 */
abstract class Controller
{
    public function __call ($method, $params) {
        if (empty($method)) {
            $this->index($params);
        } else {
            // TODO $this->redirect(404);
            echo 'Метод не существует<br>';
        }
    }

    protected function redirect($url = '/')
    {
        if (!$url) {
            $url = '/';
        }
        header('Location: ' . $url);
    }

    abstract function index(Array $params = []);

    protected function render(Array $data = [], $contentType = 'html')
    {
        switch ($contentType) {
            case 'html':
            default:
                ;
                break;
            case 'json':
                ;
                break;
        }
    }
}
