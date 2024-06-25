<?php
//App/Controllers/AboutController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Business\AuthenticationService;
use Twig\Environment;

class AboutController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        echo $this->twig->render(
            "aboutTemplate.twig",
            array(
                "page" => [
                    "title" => "About"
                ],
                "isAuthenticated" => AuthenticationService::isAuthenticated()
            )
        );
    }
}
