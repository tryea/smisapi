<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1752b9186891de890f0a08fd94529798
{
    public static $prefixesPsr0 = array (
        'E' => 
        array (
            'Endroid' => 
            array (
                0 => __DIR__ . '/..' . '/endroid/qr-code/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit1752b9186891de890f0a08fd94529798::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
