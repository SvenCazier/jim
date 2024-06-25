<?php
//App/Controllers/ContactPostController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Business\{InputService, MailService, RedirectService, RequestService, SessionService};

class ContactPostController
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
                    "name" => [InputService::NOT_EMPTY],
                    "email" => [InputService::NOT_EMPTY, InputService::EMAIL],
                    "subject" => [InputService::NOT_EMPTY],
                    "message" => [InputService::NOT_EMPTY]
                ]
            );
            if (!is_null($validatedInputs)) {
                if (MailService::sendContactMail($validatedInputs["email"], $validatedInputs["name"], $validatedInputs["subject"], $validatedInputs["message"])) {
                    SessionService::setSession("contactSuccess", true);
                }
            } else {
                SessionService::setSession("contactForm", $originalInputs);
            }
        }
        RedirectService::redirectTo(dirname($_SERVER["SCRIPT_NAME"]) . "/contact");
    }
}
