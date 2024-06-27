<?php

namespace Core;

class View
{
    public static function render($view, $data = [])
    {
        $viewPath = __DIR__ . "/../app/views/{$view}.wira.php";
        if (file_exists($viewPath)) {
            extract($data);
            ob_start();
            include($viewPath);
            $content = ob_get_clean();
            $content = self::parseContent($content, $data);
            echo $content;
        } else {
            echo "View file {$view}.wira.php not found.";
        }
    }

    private static function parseContent($content, $data)
    {
        // Parse {{ }} syntax
        $content = preg_replace_callback('/\{\{\s*(.+?)\s*\}\}/', function ($matches) use ($data) {
            return '<?php echo htmlspecialchars(' . $matches[1] . '); ?>';
        }, $content);

        // Evaluate the final PHP content
        ob_start();
        eval('?>' . $content);
        return ob_get_clean();
    }
}
