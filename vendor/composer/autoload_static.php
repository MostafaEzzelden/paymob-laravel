<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita01f05caab75cac1590af95fcd426a24
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Mgamal\\PaymobLaravel\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Mgamal\\PaymobLaravel\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInita01f05caab75cac1590af95fcd426a24::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita01f05caab75cac1590af95fcd426a24::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita01f05caab75cac1590af95fcd426a24::$classMap;

        }, null, ClassLoader::class);
    }
}