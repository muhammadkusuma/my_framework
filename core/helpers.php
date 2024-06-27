<?php
function view($view, $data = [])
{
    \Core\View::render($view, $data);
}

function redirect($path)
{
    header("Location: {$path}");
    exit;
}
