<?php
//App/Controllers/DeleteEventController.php
declare(strict_types=1);

namespace App\Controllers;

use App\Business\{AuthenticationService, EventService, InputService, RedirectService, RequestService, SessionService};

class DeleteEventController
{
    private EventService $eventService;

    public function __construct()
    {
    }

    public function handleDelete(array $params)
    {
        echo print_r($params);
        exit();
    }
}
