<?php
//bootstrap.php

declare(strict_types=1);
session_start();
require_once("vendor/autoload.php");

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

spl_autoload_register(function ($className) {
    // Convert namespace to full file path
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';

    // Check if the file exists
    if (file_exists($filePath)) {
        // Require the file
        require_once $filePath;
    }
});

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Twig\{BasePathExtension, RouteExtension};

$loader = new FilesystemLoader("App/Views");
$twig = new Environment($loader);

$twig->addExtension(new BasePathExtension());
$twig->addExtension(new RouteExtension());
