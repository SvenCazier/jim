<?php
//App/Controllers/ContactController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Business\{AuthenticationService, ErrorService, SessionService};
use Twig\Environment;

class ContactController
{
    private Environment $twig;
    private ?array $contactForm;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->contactForm = SessionService::getSession("contactForm");
        SessionService::clearSessionVariable("contactForm");
    }

    public function index()
    {
        echo $this->twig->render(
            "contactTemplate.twig",
            array(
                "page" => [
                    "title" => "Contact"
                ],
                "isAuthenticated" => AuthenticationService::isAuthenticated(),
                "contactForm" => $this->contactForm,
                "errors" => ErrorService::getErrors()
            )
        );
    }
}
