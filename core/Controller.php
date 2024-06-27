<?php

namespace Core;

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        $viewPath = __DIR__ . "/../app/views/$view.wira.php";
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "View file $view.wira.php not found.";
        }
    }
}
