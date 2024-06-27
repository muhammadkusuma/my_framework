# My PHP Framework

My PHP Framework is a simple and lightweight PHP framework inspired by Laravel, with custom file extensions for views (.wira.php).

## Requirements

- PHP 7.4 or higher
- Apache/Nginx with mod_rewrite enabled
- Composer (optional, for dependency management)

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/my_framework.git
   cd my_framework
   ```

2. **Set up your web server:**

   For Apache, add the following to your `.htaccess` file:
   ```plaintext
   RewriteEngine On
   RewriteRule ^(.*)$ index.php [QSA,L]
   AddType application/x-httpd-php .wira.php
   ```

   Ensure mod_rewrite is enabled:
   ```bash
   a2enmod rewrite
   sudo systemctl restart apache2
   ```

   For Laragon, add the following to your Apache configuration file (`C:\laragon\bin\apache\httpd-2.4.xx\conf\httpd.conf`):
   ```plaintext
   AddType application/x-httpd-php .wira.php
   ```

   Restart Apache via Laragon.

3. **Set the document root to the `public` directory:**
   Point your web server to the `public` directory inside the project.

4. **Access your project:**
   Open your browser and navigate to `http://localhost/my_framework`.

## Directory Structure

```
my_framework/
│   index.php
│   .htaccess
└───app/
    └───controllers/
        │   HomeController.php
    └───models/
    └───views/
        │   home.wira.php
└───core/
    │   autoload.php
    │   Router.php
    │   Controller.php
```

## Core Files

### `index.php`

The entry point of the application:
```php
<?php
require_once 'core/autoload.php';

use Core\Router;

$router = new Router();
$router->dispatch($_SERVER['REQUEST_URI']);
?>
```

### `core/autoload.php`

Handles class autoloading:
```php
<?php
spl_autoload_register(function ($class) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});
?>
```

### `core/Router.php`

Handles routing:
```php
<?php
namespace Core;

class Router {
    public function dispatch($uri) {
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
?>
```

### `core/Controller.php`

Base controller:
```php
<?php
namespace Core;

class Controller {
    public function view($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . "/../app/views/$view.wira.php";
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "View file $view.wira.php not found.";
        }
    }
}
?>
```

### `app/controllers/HomeController.php`

Example controller:
```php
<?php
namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller {
    public function index() {
        $this->view('home', ['message' => 'Hello, .wira Framework!']);
    }
}
?>
```

### `app/views/home.wira.php`

Example view:
```html
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h1><?php echo $message; ?></h1>
</body>
</html>
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
```