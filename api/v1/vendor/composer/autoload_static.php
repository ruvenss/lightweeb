<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1ef86147e54f71ae457f29fab056748c
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SleekDB\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SleekDB\\' => 
        array (
            0 => __DIR__ . '/..' . '/rakibtg/sleekdb/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1ef86147e54f71ae457f29fab056748c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1ef86147e54f71ae457f29fab056748c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1ef86147e54f71ae457f29fab056748c::$classMap;

        }, null, ClassLoader::class);
    }
}