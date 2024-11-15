<?php
namespace App\Library\Domain\Exceptions;

use Exception;

/**
 * Class BookNotAvailableException
 * 
 * Exception thrown when a requested book is not available.
 */
class BookNotAvailableException extends Exception
{
    /**
     * Constructor for the BookNotAvailableException class.
     * 
     * @param string $message The exception message (default is "Book not available").
     * @param int $code The exception code (default is 0).
     * @param Exception|null $previous The previous exception used for exception chaining. 
     */
    public function __construct($message = "Book not available", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Converts the exception to a string representation.
     * 
     * @return string The string representation of the exception. 
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
