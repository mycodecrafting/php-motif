<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Define NL if not defined
 */
if (!defined('NL'))
{
    define('NL', "\n");
}

/**
 * Define motif installation root
 */
define('MOTIF_ROOT', dirname(__FILE__));

/**
 * Motif class autoloading
 */
final class Motif
{

    public static function loadClass($class)
    {
        if (strpos($class, 'Motif_') !== 0)
        {
            return;
        }

        /**
         * autodiscover the path from the class name
         */
        $file = MOTIF_ROOT . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

        /**
         * Load if it exists
         */
        if (file_exists($file) === true)
        {
            require $file;
        }
    }

}

spl_autoload_register(array('Motif', 'loadClass'));
