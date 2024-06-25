<?php
//App/Business/InputService.php
declare(strict_types=1);

namespace App\Business;

use App\Business\ErrorService;
use App\Exceptions\{DataTypeException, EmptyInputException};

class InputService
{
    const EMAIL = "email";
    const PASSWORD = "password";
    const INTEGER = "whole number";
    const DATE = "date";
    const NOT_EMPTY = "not empty";

    public static function validateInputs(array $inputs, array $keys): ?array
    {
        if (empty($keys)) return null;
        $inputs = self::sanitizeInput($inputs);
        if (self::emptyCheckFailed($inputs, $keys)) return null;

        $areDataTypesValid = true;
        foreach ($keys as $key => $dataTypes) {
            $dataTypes = array_filter($dataTypes, function ($dataType) {
                return $dataType !== self::NOT_EMPTY;
            });

            if (is_array($inputs[$key])) {
                foreach ($inputs[$key] as $input) {
                    if (self::isInvalidDataType($key, $input, $dataTypes)) $areDataTypesValid = false;
                }
            } else {
                if (self::isInvalidDataType($key, $inputs[$key], $dataTypes)) $areDataTypesValid = false;
            }
        }
        return $areDataTypesValid ? $inputs : null;
    }

    private static function sanitizeInput(array $inputs): array
    {
        foreach ($inputs as $key => $value) {
            if (is_array($inputs[$key])) {
                foreach ($inputs[$key] as $arrKey => $arrValue) {
                    $inputs[$key][$arrKey] = stripslashes(htmlspecialchars(trim($arrValue ?? ""), ENT_QUOTES));
                }
            } else {
                $inputs[$key] = stripslashes(htmlspecialchars(trim($value ?? ""), ENT_QUOTES));
            }
        }
        return $inputs;
    }

    private static function emptyCheckFailed(array $inputs, array $keys): bool
    {
        $isValid = true;
        foreach ($keys as $key => $value) {
            if (!array_key_exists($key, $inputs)) return true;
            try {
                if (empty($inputs[$key]) && in_array(self::NOT_EMPTY, $value)) {
                    throw new EmptyInputException();
                }
            } catch (EmptyInputException $e) {
                ErrorService::setError($e->getMessage(), $key);
                $isValid = false;
            }
        }
        return !$isValid;
    }

    private static function isInvalidDataType($key, $input, $dataTypes): bool
    {
        try {
            if (empty($dataTypes)) return false;
            foreach ($dataTypes as $dataType) {
                if (match ($dataType) {
                    self::EMAIL => filter_var($input, FILTER_VALIDATE_EMAIL),
                    self::PASSWORD => is_string($input),
                    self::INTEGER => self::isInt($input),
                    self::DATE => strtotime($input)
                }) {
                    return false;
                }
            }
            throw new DataTypeException($dataTypes);
        } catch (DataTypeException $e) {
            ErrorService::setError($e->getMessage(), $key);
            return true;
        }
    }

    private static function isInt(string $input): bool
    {
        // NOTEMPTY was checked before, this makes optional input validation possible
        if (!$input) $input = 0;
        if (is_numeric($input)) {
            return is_int(1 * $input);
        }
        return false;
    }
}
