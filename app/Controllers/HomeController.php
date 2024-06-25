<?php
//App/Controllers/HomeController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Business\AuthenticationService;
use Twig\Environment;

class HomeController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        echo $this->twig->render(
            "homeTemplate.twig",
            array(
                "page" => [
                    "title" => "Justice Is Mine",
                    "home" => true
                ],
                "isAuthenticated" => AuthenticationService::isAuthenticated()
            )
        );
    }
}
