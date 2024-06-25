<?php
//App/Business/LoggerService.php

namespace App\Business;

class LoggerService
{
    const LOGIN = "login.log";
    const ERROR = "error.log";

    public static function logError(string $logFile, string $value, bool $status = true): string
    {
        if (in_array($logFile, [self::LOGIN, self::ERROR], true)) {
            $timestamp = date('Y-m-d H:i:s');
            $uniqueId = uniqid();
            $logEntry = "[$timestamp][$uniqueId]";
            if ($logFile === self::LOGIN) {
                $statusMessage = $status ? "SUCCESS" : "FAILED";
                $logEntry .= "[$statusMessage]";
            }
            $logEntry .= $value . PHP_EOL;
            file_put_contents(__DIR__ . "/../../private/logs/$logFile", $logEntry, FILE_APPEND);
            return $uniqueId;
        }
    }
}
