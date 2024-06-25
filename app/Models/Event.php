<?php
//App\Models\Event
declare(strict_types=1);

namespace App\Models;

use App\Constants\VoiceChannels;

class Event
{
    private string $id;
    private string $guildId;
    private string $channelId;
    private string $name;
    private string $description;
    private int $scheduledStartTime;
    private int $eventTypeId;

    function __construct(string $id, string $name, string $description, int $scheduledStartTime, int $eventTypeId, string $channelId, string $guildId = "1200488927916732518")
    {
        $this->id = $id;
        $this->guildId = $guildId;
        $this->channelId = $channelId;
        $this->name = $name;
        $this->description = $description;
        $this->scheduledStartTime = $scheduledStartTime;
        $this->eventTypeId = $eventTypeId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGuildId(): string
    {
        return $this->guildId;
    }

    public function getChannelId(): string
    {
        return $this->channelId;
    }

    public function getChannelName(): string
    {
        foreach (VoiceChannels::CHANNELS as $channel) {
            if ($channel["id"] === $this->channelId) {
                return $channel["name"];
            }
        }
        return "No voice channel";
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getScheduledStartTime(): int
    {
        return $this->scheduledStartTime;
    }

    public function getScheduledStartFullUTCString(): string
    {
        return gmdate('D M d Y H:i:s \G\M\TO (e)', $this->scheduledStartTime);
    }

    public function getScheduledStartUTCTime(): string
    {
        return sprintf("%s %s", gmdate("H:i", $this->scheduledStartTime), "UTC+00:00");
    }

    public function getEventTypeId(): int
    {
        return $this->eventTypeId;
    }
}
