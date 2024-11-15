<?php
namespace App\Library\Domain\Exceptions;

use Exception;

/**
 * Class ResourceNotFoundException
 * 
 * Exception thrown when a specified resource is not found. 
 * 
 */
class ResourceNotFoundException extends Exception
{
    /**
     * Constructor for the ResourceNotFoundException class.
     * 
     * @param string $resourceType The type of the resource that was not found.
     * @param int $resourceId The ID of the resource that was not found.
     * @param int $code The exception code (default is 0).
     * @param Exception|null $previous The previous exception used for exception chaining. 
     * 
     */
    public function __construct($resourceType, $resourceId, $code = 0, Exception $previous = null)
    {
        $message = "Resource $resourceType with Id $resourceId was not found.";
        parent::__construct($message, $code, $previous);
    }

    /**
     * Converts the exception to a string representation.
     * 
     * @return string The string representation of the exception. 
     * 
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
