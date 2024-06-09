<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitd0d61f04a64c70fe4eafca8138afafc0
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitd0d61f04a64c70fe4eafca8138afafc0', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitd0d61f04a64c70fe4eafca8138afafc0', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitd0d61f04a64c70fe4eafca8138afafc0::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}