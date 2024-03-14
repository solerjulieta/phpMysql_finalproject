<?php

spl_autoload_register(function($className)
{
    $className = substr($className, 3);
    $filename = __DIR__ . '/../clases/' .$className . '.php';
    if(file_exists($filename)){
        require_once $filename;
    }
});