<?php

namespace System;

class App
{

    public static function run()
    {
        $path = $_SERVER['REQUEST_URI'];
        $pathParts = explode('/', $path);
        $controller = $pathParts[1];
        $action = $pathParts[2];

        if (!$action)
            $action = 'index';

        if (!$controller)
            $controller = 'main';

        $controller = 'Controllers\\' . $controller . 'Controller';
        $action = 'action' . ucfirst($action);

        if (!class_exists($controller)) {
            throw new \ErrorException('Controller does not exist');
        }
        
        $objController = new $controller;
        
        if (!method_exists($objController, $action)) {
            throw new \ErrorException('action does not exist');
        }
        
        $objController->$action();
    }
}

