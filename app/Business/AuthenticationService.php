<?php
//App/Business/AuthenticationService.php

namespace App\Business;

use App\Business\{ErrorService, SessionService};
use App\Models\User;
use \Exception;
use \Throwable;

class AuthenticationService
{
    public static function login(string $email, string $password): bool
    {
        try {
            // Generate private key
            $phpPrivateKey = openssl_pkey_new([
                "ec" => [
                    "curve_name" => "secp256k1",
                ],
            ]);

            // Extract public key
            $phpPublicKeyDetails = openssl_pkey_get_details($phpPrivateKey);

            $phpPublicKey = $phpPublicKeyDetails["key"];

            // Send public key server for key exchange
            $response = self::sendRequest("http://localhost:3000/key-exchange", ["publicKey" => bin2hex($phpPublicKey)]);

            // Convert received public key
            $nodePublicKey = hex2bin($response["publicKey"]);

            $sharedSecretKey = self::generateSharedSecretKey($phpPrivateKey, $nodePublicKey);


            echo print_r($sharedSecretKey);
            exit();

            // Generate AES key
            $aesKey = openssl_random_pseudo_bytes(32); // 256-bit AES key

            // Encrypt AES key with Node's public key
            openssl_public_encrypt($aesKey, $encryptedAesKey, $nodePublicKey);

            // Encrypt login data with AES key
            $encryptedLoginData = openssl_encrypt("email=$email&password=$password", "aes-256-ctr", $aesKey, OPENSSL_RAW_DATA, hex2bin("00000000000000000000000000000000"));

            // Send encrypted AES key and login data to Node.js server
            $response = self::sendRequest("http://localhost:3000/login", ["sharedSecretKey" => $sharedSecretKey, "encryptedAesKey" => bin2hex($encryptedAesKey), "encryptedLoginData" => bin2hex($encryptedLoginData)]);

            // Decrypt and process login response
            $decryptedLoginData = openssl_decrypt(hex2bin($response["encryptedLoginData"]), "aes-256-ctr", $aesKey, OPENSSL_RAW_DATA, hex2bin("00000000000000000000000000000000"));
            parse_str($decryptedLoginData, $loginData);

            // Process login data and set session
            $user = new User($loginData["id"], $loginData["email"], $loginData["accessToken"], $loginData["refreshToken"]);
            SessionService::setSession("user", $user);
            LoggerService::logError(LoggerService::LOGIN, $email, true);
            return true;
        } catch (Throwable $e) {
            LoggerService::logError(LoggerService::LOGIN, $email, false);
            ErrorService::setLoggedError(LoggerService::logError(LoggerService::ERROR, $e->getMessage()));
            return false;
        }
    }

    public static function logout(): void
    {
        SessionService::clearSessionVariable("user");
    }

    public static function isAuthenticated(): bool
    {
        return false;
    }

    private static function sendRequest($url, $data): ?array
    {
        try {
            $options = [
                "http" => [
                    "method" => "POST",
                    "header" => "Content-Type: application/json",
                    "content" => json_encode($data)
                ]
            ];
            $context = stream_context_create($options);
            return json_decode(file_get_contents($url, false, $context), true);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    private static function generateSharedSecretKey($phpPrivateKey, $nodePublicKey)
    {
        openssl_private_decrypt($nodePublicKey, $sharedSecret, $phpPrivateKey);

        return $sharedSecret;
    }
}
