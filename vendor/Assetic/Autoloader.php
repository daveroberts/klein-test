<?php
/**
 * Autoloads Assetic classes.
 *
 * @author  Joeri Verdeyen <info@jverdeyen.be>
 */
class Assetic_Autoloader
{
    /**
     * Registers Assetic_Autoloader as an SPL autoloader.
     */
    static public function register()
    {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));
    }

    /**
     * Handles autoloading of classes.
     *
     * @param  string  $class  A class name.
     *
     * @return boolean Returns true if the class has been loaded
     */
    static public function autoload($class)
    {
        if (0 !== strpos($class, 'Assetic')) {
            return;
        }

        if (file_exists($file = dirname(__FILE__).'/../'.str_replace('\\', '/', $class).'.php')) {
            require $file;
        }

    }
}