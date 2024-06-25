<?php
//index.php
declare(strict_types=1);

require_once("bootstrap.php");

use App\Router\Router;
use App\Controllers\{
    HomeController,
    AboutController,
    EventController,
    CreateEventController,
    DeleteEventController,
    CalculatorsController,
    ContactController,
    ContactPostController,
    DiscordController,
    LoginController,
    LoginPostController,
    CookiePolicyController
};

// Routes
$basePath = dirname($_SERVER['SCRIPT_NAME']);
$routes = [
    'GET' => [
        $basePath . "/" => HomeController::class . "@index",
        $basePath . "/about" => AboutController::class . "@index",
        $basePath . "/events" => EventController::class . "@index",
        $basePath . "/events/delete/:id" => DeleteEventController::class . "@handleDelete",
        $basePath . "/calculators" => CalculatorsController::class . "@index",
        $basePath . "/contact" => ContactController::class . "@index",
        $basePath . "/discord" => DiscordController::class . "@index",
        $basePath . "/login" => LoginController::class . "@index",
        $basePath . "/cookie" => CookiePolicyController::class . "@index"
    ],
    'POST' => [
        $basePath . "/events" => CreateEventController::class . "@handlePost",
        $basePath . "/contact" => ContactPostController::class . "@handlePost",
        $basePath . "/login" => LoginPostController::class . "@handlePost"
    ]
];

// Create router instance
$router = new Router($routes);

// Handle request
$uri = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];
$router->handleRequest($uri, $method, $twig);
