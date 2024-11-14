<?php
namespace App\Library\Domain\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    public function __construct($resourceType, $resourceId, $code = 0, Exception $previous = null)
    {
        $message = "O recurso $resourceType com ID $resourceId não foi encontrado.";
        parent::__construct($message, $code, $previous);
    }
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
