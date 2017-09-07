<?php

namespace App\Controllers;

/**
 *
 */
class Main extends \App\Controller
{

    function __construct()
    {
        ;
    }

    public function index(Array $params = [])
    {
        echo 'I am method Index from Main!';
    }
}
