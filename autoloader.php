<?php

// Auto load classes that are instantiated
spl_autoload_register('AutoLoad');

/**
 * AutoLoad - Automatically load any class that is instantiated.
 *
 * @param  string $className
 *
 * @return void
 */
function AutoLoad($className)
{
    $path       = 'classes/';
    $extension  = '.class.php';
    $fullPath   = $path . $className . $extension;

    require_once $fullPath;
}

?>