<?php
//App/Exceptions/InvalidUserCredentialsException.php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class InvalidUserCredentialsException extends Exception
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        $message = "Invalid credentials, please try again.";
        parent::__construct($message, $code, $previous);
    }
}
