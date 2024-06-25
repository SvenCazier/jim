<?php
//App\Constants\EventTypes
declare(strict_types=1);

namespace App\Constants;

class EventTypes
{
    public const TYPES = [
        ["id" => "1", "name" => "Bear Hunt", "duration" => 30],
        ["id" => "2", "name" => "Crazy Joe", "duration" => 30],
        ["id" => "3", "name" => "Foundry", "duration" => 60],
        ["id" => "4", "name" => "Sunfire Castle", "duration" => 480],
        ["id" => "5", "name" => "State vs State", "duration" => 480]
    ];

    public static function getEventId(string $eventName): int
    {
        foreach (self::TYPES as $event) {
            if ($event["name"] === $eventName) {
                return (int) $event["id"];
            }
        }
        return 6;
    }

    public static function getEventDuration(string $eventName): int
    {
        foreach (self::TYPES as $event) {
            if ($event["name"] === $eventName) {
                return (int) $event["duration"];
            }
        }
        return 30;
    }
}
