<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit32f529fdd4b7bb8988d34e8415e9929f
{
    public static $prefixLengthsPsr4 = array (
        'y' => 
        array (
            'yiiplus\\appversion\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'yiiplus\\appversion\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit32f529fdd4b7bb8988d34e8415e9929f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit32f529fdd4b7bb8988d34e8415e9929f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}