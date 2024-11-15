<?php
namespace App\Library\Domain\Exceptions;

use Exception;

/**
 * Class ExceedsLoanLimitException
 * 
 * Exception thrown when a user exceeds the allowed loan limit. 
 */
class ExceedsLoanLimitException extends Exception
{
    /**
     * Constructor for the ExceedsLoanLimitException class.
     * 
     * @param string $message The exception message (default is "User has exceeded the loan limit").
     * @param int $code The exception code (default is 0).
     * @param Exception|null $previous The previous exception used for exception chaining. 
     */
    public function __construct($message = "User has exceeded the loan limit", $code = 0, Exception $previous = null)
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
