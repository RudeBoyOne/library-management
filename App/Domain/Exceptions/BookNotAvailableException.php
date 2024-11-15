<?php
namespace App\Library\Domain\Exceptions;

use Exception;

class BookNotAvailableException extends Exception
{
    public function __construct($message = "Book not available", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
