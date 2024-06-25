<?php
//App/Data/InviteDAO.php

namespace App\Data;

use \Throwable;

class InviteDAO
{
    private string $apiUrl = "http://localhost:10000/invite";

    public function getInvite(): array
    {
        try {
            // Fetch data from the API
            $data = file_get_contents($this->apiUrl);

            // Check if data was fetched successfully
            if ($data === false) {
                throw new Throwable('Error fetching data from the API');
            }

            // Convert JSON string to associative array
            $jsonData = json_decode($data, true);

            // Check if JSON decoding was successful
            if ($jsonData === null) {
                throw new Throwable('Error decoding JSON data');
            }

            return $jsonData;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
