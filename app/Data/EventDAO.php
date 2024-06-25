<?php
//App/Data/EventDAO.php

namespace App\Data;

use \RuntimeException;
use \Throwable;

class EventDAO
{
    private string $apiUrl = "http://localhost:10000/event";

    public function getEvents(): array
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 1,
            ]
        ]);

        try {
            $data = file_get_contents($this->apiUrl, false, $context);

            if ($data === false) {
                throw new Throwable('Error fetching data from the API');
            }

            $jsonData = json_decode($data, true);

            if ($jsonData === null) {
                throw new Throwable('Error decoding JSON data');
            }

            return $jsonData;
        } catch (Throwable $e) {
            throw $e;
        }
    }
    public function createEvent(array $data): void
    {
        try {

            $ch = curl_init($this->apiUrl);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new \RuntimeException('cURL Error: ' . curl_error($ch));
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($httpCode !== 201) {
                    throw new \RuntimeException('API Error: Unexpected HTTP status code ' . $httpCode);
                }
            }
        } catch (Throwable $e) {
            throw $e;
        } finally {
            curl_close($ch);
        }
    }

    public function updateEvent(array $data): void
    {
    }

    public function deleteEvent(int $eventId): void
    {
    }
}
