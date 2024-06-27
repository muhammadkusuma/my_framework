<?php

namespace Core;

class Router
{
    public function dispatch($uri)
    {
        $segments = explode('/', trim($uri, '/'));
        $controller = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'HomeController';
        $method = !empty($segments[1]) ? $segments[1] : 'index';

        $controllerClass = "App\\Controllers\\$controller";
        if (class_exists($controllerClass)) {
            $controllerInstance = new $controllerClass();
            if (method_exists($controllerInstance, $method)) {
                $controllerInstance->$method();
            } else {
                echo "Method $method not found in controller $controllerClass.";
            }
        } else {
            echo "Controller $controllerClass not found.";
        }
    }
}
