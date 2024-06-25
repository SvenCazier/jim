<?php
//App/Controllers/DiscordController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Business\AuthenticationService;
use Twig\Environment;

class DiscordController
{
    private Environment $twig;
    private string $inviteURL = "https://discord.gg/sbtGpaRSeM";

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        echo $this->twig->render(
            "discordTemplate.twig",
            array(
                "page" => [
                    "title" => "Discord"
                ],
                "isAuthenticated" => AuthenticationService::isAuthenticated(),
                "inviteURL" => $this->inviteURL
            )
        );
    }
}
