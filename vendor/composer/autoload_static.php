<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2466eed2c45de3560a39b4d26d22a4c8
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Picqer\\Barcode\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Picqer\\Barcode\\' => 
        array (
            0 => __DIR__ . '/..' . '/picqer/php-barcode-generator/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2466eed2c45de3560a39b4d26d22a4c8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2466eed2c45de3560a39b4d26d22a4c8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2466eed2c45de3560a39b4d26d22a4c8::$classMap;

        }, null, ClassLoader::class);
    }
}
