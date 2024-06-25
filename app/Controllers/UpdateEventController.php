<?php
//App/Controllers/UpdateEventController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Business\{AuthenticationService, EventService, InputService, RedirectService, RequestService, SessionService};

class UpdateEventController
{
    private EventService $eventService;

    public function __construct()
    {
    }

    public function handlePost()
    {
        if (RequestService::isPost() && AuthenticationService::isAuthenticated()) {
            $originalInputs = RequestService::postArray();
            $validatedInputs = InputService::validateInputs(
                $originalInputs,
                [
                    "eventname" => [InputService::NOT_EMPTY],
                    "date" => [InputService::NOT_EMPTY, InputService::DATE],
                    "timeZoneOffset" => [InputService::NOT_EMPTY, InputService::INTEGER],
                    "voicechannel" => [InputService::INTEGER],
                    "description" => [],
                ]
            );
            if (!is_null($validatedInputs)) {
                $this->eventService = new EventService();
                if (!$this->eventService->updateEvent(
                    $validatedInputs["eventname"],
                    $validatedInputs["date"],
                    (int)$validatedInputs["timeZoneOffset"],
                    $validatedInputs["voicechannel"],
                    $validatedInputs["description"]
                )) {
                    // Something went wrong trying to update the event
                    SessionService::setSession("createEventForm", $originalInputs);
                }
            } else {
                // Invalid input values
                SessionService::setSession("createEventForm", $originalInputs);
            }
        }
        RedirectService::redirectTo(dirname($_SERVER["SCRIPT_NAME"]) . "/events");
    }
}
