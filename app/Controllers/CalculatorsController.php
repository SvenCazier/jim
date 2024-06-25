<?php
//App/Controllers/CalculatorsController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Business\AuthenticationService;
use Twig\Environment;

class CalculatorsController
{
    private Environment $twig;
    private array $scripts = ["resizer.js", "troopCalculator.js"];

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        echo $this->twig->render(
            "calculatorsTemplate.twig",
            array(
                "page" => [
                    "title" => "Calculators",
                    "scripts" => $this->scripts
                ],
                "isAuthenticated" => AuthenticationService::isAuthenticated()
            )
        );
    }
}
