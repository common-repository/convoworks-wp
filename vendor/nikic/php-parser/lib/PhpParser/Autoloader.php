<?php

namespace Convoworks\PhpParser;

/**
 * @codeCoverageIgnore
 */
class Autoloader
{
    /** @var bool Whether the autoloader has been registered. */
    private static $registered = \false;
    /**
     * Registers PhpParser\Autoloader as an SPL autoloader.
     *
     * @param bool $prepend Whether to prepend the autoloader instead of appending
     */
    public static function register($prepend = \false)
    {
        if (self::$registered === \true) {
            return;
        }
        \spl_autoload_register(array(__CLASS__, 'autoload'), \true, $prepend);
        self::$registered = \true;
    }
    /**
     * Handles autoloading of classes.
     *
     * @param string $class A class name.
     */
    public static function autoload($class)
    {
        if (0 === \strpos($class, 'PhpParser\\')) {
            $fileName = __DIR__ . \strtr(\substr($class, 9), '\\', '/') . '.php';
            if (\file_exists($fileName)) {
                require $fileName;
            }
        }
    }
}
