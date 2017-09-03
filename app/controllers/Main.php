<?php
namespace App\Controllers;
use Core\Controller;

/**
 *
 */
class Main extends Controller
{

    function __construct()
    {
        ;
    }

    public function index($value='')
    {
        echo 'I am method Index from Main!';
    }
}
