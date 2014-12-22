<?php

/** @var \Composer\Autoload\ClassLoader $autoloader */
$autoloader = __DIR__.'/../../vendor/autoload.php';
if ( file_exists( $autoloader ) )
	require $autoloader;

$functions = __DIR__.'/hook-functions.php';
if ( is_file( $functions ) )
	require $functions;