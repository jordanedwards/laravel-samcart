<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9492120717b8bec8db749540c3c04f32
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Orchardcity\\LaravelSamcart\\' => 27,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Orchardcity\\LaravelSamcart\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9492120717b8bec8db749540c3c04f32::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9492120717b8bec8db749540c3c04f32::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9492120717b8bec8db749540c3c04f32::$classMap;

        }, null, ClassLoader::class);
    }
}
