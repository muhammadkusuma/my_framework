<?php
require_once 'core/autoload.php';

use Core\Router;

$router = new Router();
$router->dispatch($_SERVER['REQUEST_URI']);
