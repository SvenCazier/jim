<?php
//App/Controllers/LoginController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Business\{AuthenticationService, ErrorService, SessionService};
use Twig\Environment;

class LoginController
{
    private Environment $twig;
    private ?string $email;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->email = SessionService::getSession("email");
        SessionService::clearSessionVariable("email");
    }

    public function index()
    {
        echo $this->twig->render(
            "loginTemplate.twig",
            array(
                "page" => [
                    "title" => "Login"
                ],
                "email" => $this->email,
                "isAuthenticated" => AuthenticationService::isAuthenticated(),
                "errors" => ErrorService::getErrors()
            )
        );
    }
}
