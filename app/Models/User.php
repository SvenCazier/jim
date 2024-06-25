<?php
//App\Models\User
declare(strict_types=1);

namespace App\Models;

class User
{
    private int $id;
    private string $email;
    private string $accessToken;
    private string $refreshToken;

    public function __construct($id, $email, $accessToken, $refreshToken)
    {
        $this->id = $id;
        $this->email = $email;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
