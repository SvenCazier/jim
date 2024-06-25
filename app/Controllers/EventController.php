<?php
//App/Controllers/EventController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Business\{AuthenticationService, EventService, ErrorService, SessionService};
use App\Constants\{EventTypes, VoiceChannels};
use Twig\Environment;

class EventController
{
    private Environment $twig;
    private EventService $eventService;
    private ?array $events;
    private ?array $createEventForm;
    private array $scripts = ["events.js"];

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->eventService = new EventService();
        $this->events = $this->eventService->getEvents();
        $this->createEventForm = [];

        // If authenticated add the scripts to create/update/delete events and get any eventform errors
        if (AuthenticationService::isAuthenticated()) {
            $this->scripts[] = "createEvents.js";
            $this->createEventForm = SessionService::getSession("createEventForm");
            SessionService::clearSessionVariable("createEventForm");
        }
    }
    public function index()
    {
        echo $this->twig->render(
            "eventTemplate.twig",
            array(
                "page" => [
                    "title" => "Events",
                    "scripts" => $this->scripts
                ],
                "events" => $this->events,
                "createEventForm" => $this->createEventForm,
                "isAuthenticated" => AuthenticationService::isAuthenticated(),
                "eventTypes" => EventTypes::TYPES,
                "voiceChannels" => VoiceChannels::CHANNELS,
                "errors" => ErrorService::getErrors()
            )
        );
    }
}
