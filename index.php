<?php

require_once 'application/lib/Dev.php';
use application\core\Router;
//use application\controllers\AccountController;

// для классов подключения автозагрузка
spl_autoload_register(function ($class){
   // include "classes/".$class.'.class.php';
    $path = str_replace('\\','/',$class.'.php');

    if (file_exists($path)){
        require $path;
    }
});


session_start();

$router = new Router;
$router -> run();