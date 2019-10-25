<?php

// Auto load classes that are instantiated
spl_autoload_register('AutoLoad');

function AutoLoad($className)
{
    $path       = './';
    $extension  = '.class.php';
    $fullPath   = $path . $className . $extension;

    require_once $fullPath;
}

?>