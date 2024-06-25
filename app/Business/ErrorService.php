<?php
//App/Business/ErrorService.php
declare(strict_types=1);

namespace App\Business;

use App\Business\SessionService;

class ErrorService
{

    public static function setLoggedError(string $logId, string | null $msg = null): void
    {
        $preText = $msg ?? "An error occurred";
        self::setError("$preText, for more information please contact the site administrator and provide them with the following error message: $logId");
    }

    public static function setError(string $errormsg, string $key = null): void
    {
        $errors = self::getErrors();
        if ($key) $errors[$key] = $errormsg;
        else $errors[] = $errormsg;

        SessionService::setSession("errors", $errors);
    }

    public static function getErrors(): array
    {
        $errors = SessionService::getSession("errors") ?? [];
        if ($errors) {
            self::clearErrors();
        }
        return $errors;
    }

    public static function clearErrors(): void
    {
        SessionService::clearSessionVariable("errors");
    }
}
