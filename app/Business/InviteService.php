<?php
//App/Business/InviteService.php

namespace App\Business;

use App\Business\{ErrorService};
use App\Data\InviteDAO;
use \Exception;
use \Throwable;

class InviteService
{
    private InviteDAO $inviteDAO;

    public function getInviteURL(): ?string
    {
        try {
            $this->inviteDAO = new inviteDAO();
            $invite = $this->inviteDAO->getInvite();
            return $invite["url"];
        } catch (Throwable $e) {
            ErrorService::setLoggedError(LoggerService::logError(LoggerService::ERROR, $e->getMessage()));
            return "";
        }
    }

    public function createEvent(string $eventName, string $dateString, int $timezoneOffset, string $voiceChannel = "", string $description = ""): bool
    {
        try {
            $timestamp = strtotime($dateString . $timezoneOffset . ' minutes');
            return true;
        } catch (Throwable $e) {
            ErrorService::setLoggedError(LoggerService::logError(LoggerService::ERROR, $e->getMessage()));
            return false;
        }
    }
}
