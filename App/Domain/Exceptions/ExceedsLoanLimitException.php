<?php
namespace App\Library\Domain\Exceptions;

use Exception;

class ExceedsLoanLimitException extends Exception
{
    public function __construct($message = "User has exceeded the loan limit", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
        
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
