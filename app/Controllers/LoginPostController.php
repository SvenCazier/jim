<?php
//App/Controllers/LoginPostController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Business\{AuthenticationService, InputService, RedirectService, RequestService, SessionService};

class LoginPostController
{
    public function __construct()
    {
    }

    public function handlePost()
    {
        if (RequestService::isPost()) {
            $originalInputs = RequestService::postArray();
            $validatedInputs = InputService::validateInputs(
                $originalInputs,
                [
                    "email" => [InputService::NOT_EMPTY, InputService::EMAIL],
                    "password" => [InputService::NOT_EMPTY, InputService::PASSWORD]
                ]
            );
            if (!is_null($validatedInputs)) {
                if (AuthenticationService::login($validatedInputs["email"], $validatedInputs["password"])) {
                    RedirectService::redirectTo(dirname($_SERVER["SCRIPT_NAME"]) . "/");
                }
            }
            SessionService::setSession("email", $originalInputs["email"]);
            RedirectService::redirectTo(dirname($_SERVER["SCRIPT_NAME"]) . "/login");
        }
        RedirectService::redirectTo(dirname($_SERVER["SCRIPT_NAME"]) . "/");
    }
}
