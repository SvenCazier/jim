<?php
//App/Controllers/CookiePolicyController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Business\AuthenticationService;
use Twig\Environment;

class CookiePolicyController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        echo $this->twig->render(
            "cookieTemplate.twig",
            array(
                "page" => [
                    "title" => "Cookie Policy"
                ],
                "isAuthenticated" => AuthenticationService::isAuthenticated()
            )
        );
    }
}
