<?php
//App/Exceptions/EmptyInputException.php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class EmptyInputException extends Exception
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        $message = "Please fill in this field.";
        parent::__construct($message, $code, $previous);
    }
}
