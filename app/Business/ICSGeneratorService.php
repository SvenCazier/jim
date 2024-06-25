<?php
//App/Business/ICSGeneratorService.php
declare(strict_types=1);

namespace App\Services;

class ICSGeneratorService
{
    private $icsDirectory = "/../Temp/ICS";

    public static function createEvent($filename, $eventDetails)
    {
        $icsContent = self::generateICSContent($eventDetails);

        file_put_contents(self::$icsDirectory . $filename . '.ics', $icsContent);
    }

    public static function readEvent($filename)
    {
        $icsFilePath = self::$icsDirectory . $filename . '.ics';

        if (file_exists($icsFilePath)) {
            return file_get_contents($icsFilePath);
        } else {
            return false;
        }
    }

    public static function updateEvent($filename, $eventDetails)
    {
        self::createEvent($filename, $eventDetails);
    }

    public static function deleteEvent($filename)
    {
        $icsFilePath = self::$icsDirectory . $filename . '.ics';

        if (file_exists($icsFilePath)) {
            unlink($icsFilePath);
            return true;
        } else {
            return false;
        }
    }

    private static function generateICSContent($eventDetails)
    {
        ob_start();

        echo "BEGIN:VCALENDAR\n";
        echo "VERSION:2.0\n";
        echo "PRODID:-//Your Company//Your App//EN\n";
        echo "BEGIN:VEVENT\n";
        echo "UID:" . uniqid() . "\n";
        echo "SUMMARY:" . $eventDetails['summary'] . "\n";
        echo "DESCRIPTION:" . $eventDetails['description'] . "\n";
        echo "DTSTART:" . date('Ymd\THis', strtotime($eventDetails['start_time'])) . "\n";
        echo "DTEND:" . date('Ymd\THis', strtotime($eventDetails['end_time'])) . "\n";
        echo "LOCATION:" . $eventDetails['location'] . "\n";
        echo "END:VEVENT\n";
        echo "END:VCALENDAR\n";

        $icsContent = ob_get_contents();
        ob_end_clean();

        return $icsContent;
    }
}
