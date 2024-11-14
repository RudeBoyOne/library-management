<?php
namespace App\Library\Domain\Entities\UserEntities;

use App\Library\Domain\Entities\UserEntities\User;
use JsonSerializable;

/**
 * Class Student
 *
 * Represents a student who can make up to 1 loan
 */
class Student extends User implements JsonSerializable
{

    /**
     * Constructor of the Student class
     *
     * Initializes the maximum loan amount to 1
     */
    public function __construct()
    {
        $this->setLoanAmount(1);
    }

    /**
     * Checks if the student can make more loans
     * @param int $amountCurrentLoans Current number of loans
     * @return bool Returns true if the student can make more loans.
     */
    public function canTakeOutMoreLoans(int $amountCurrentLoans): bool
    {
        return $amountCurrentLoans <= $this->getLoanAmount();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize():array
    {
        return [
            "name" => $this->getName(),
            "e-mail" => $this->getEmail(),
            "registration" => $this->getRegistration(),
            "type user" => $this->getRole()->getName()
        ];
    }
}
