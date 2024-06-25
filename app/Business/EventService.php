<?php
//App/Business/EventService.php

namespace App\Business;

use App\Business\ErrorService;
use App\Constants\Errors;
use App\Constants\EventTypes;
use App\Data\EventDAO;
use App\Models\Event;
use \Throwable;

class EventService
{
    private EventDAO $eventDAO;

    public function getEvents(): ?array
    {
        try {
            $this->eventDAO = new EventDAO();
            $eventsArray = [];
            $events = $this->eventDAO->getEvents();
            $date = $this->getDate($events[0]["scheduledStartTime"]);
            foreach ($events as $event) {
                $timestamp = $event["scheduledStartTime"];
                $newDate = $this->getDate($timestamp);
                if ($date !== $newDate) $date = $newDate;
                $eventsArray[$date][] = new Event(
                    $event["id"],
                    $event["name"],
                    $event["description"],
                    (int)$timestamp / 1000,
                    (int)$event["eventTypeId"],
                    $event["channelId"],
                    $event["guildId"]
                );
            }
            return $eventsArray;
        } catch (Throwable $e) {
            ErrorService::setLoggedError(LoggerService::logError(LoggerService::ERROR, $e->getMessage()), Errors::FETCH_EVENT);
            return null;
        }
    }

    private function getDate(string $timestamp)
    {
        return gmdate('Y-m-d', intval($timestamp) / 1000);
    }

    public function createEvent(string $eventName, string $dateString, int $timezoneOffset, string $voiceChannel = "", string $description = ""): bool
    {
        try {
            $this->eventDAO = new EventDAO();
            $scheduledStartTime = strtotime($dateString . $timezoneOffset . ' minutes') * 1000;
            $this->eventDAO->createEvent(array(
                "name" => $eventName,
                "typeId" => EventTypes::getEventId($eventName),
                "scheduledStartTime" => $scheduledStartTime,
                "scheduledEndTime" => $scheduledStartTime + (EventTypes::getEventDuration($eventName) * 60 * 1000),
                "description" => $description,
                "channel" => $voiceChannel
            ));
            return true;
        } catch (Throwable $e) {
            ErrorService::setLoggedError(LoggerService::logError(LoggerService::ERROR, $e->getMessage()), Errors::CREATE_EVENT);
            return false;
        }
    }

    public function updateEvent(string $eventName, string $dateString, int $timezoneOffset, string $voiceChannel = "", string $description = ""): bool
    {
        try {
            $this->eventDAO = new EventDAO();
            $scheduledStartTime = strtotime($dateString . $timezoneOffset . ' minutes') * 1000;
            $this->eventDAO->createEvent(array(
                "name" => $eventName,
                "typeId" => EventTypes::getEventId($eventName),
                "scheduledStartTime" => $scheduledStartTime,
                "scheduledEndTime" => $scheduledStartTime + (EventTypes::getEventDuration($eventName) * 60 * 1000),
                "description" => $description,
                "channel" => $voiceChannel
            ));
            return true;
        } catch (Throwable $e) {
            ErrorService::setLoggedError(LoggerService::logError(LoggerService::ERROR, $e->getMessage()), Errors::UPDATE_EVENT);
            return false;
        }
    }

    public function deleteEvent(int $eventId): bool
    {
        try {
            $this->eventDAO = new EventDAO();
            $this->eventDAO->deleteEvent($eventId);
            return true;
        } catch (Throwable $e) {
            ErrorService::setLoggedError(LoggerService::logError(LoggerService::ERROR, $e->getMessage()), Errors::DELETE_EVENT);
            return false;
        }
    }
}
