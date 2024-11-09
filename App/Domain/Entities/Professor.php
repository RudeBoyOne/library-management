<?php
namespace App\Library\Domain\Entities;

use App\Library\Domain\Entities\User;

/**
 * Class Professor
 * 
 * Represents a professor who can make up to 3 loans
 */
class Professor extends User
{

    /**
     * Constructor of the Professor class
     * 
     * Initializes the maximum loan amount to 3.
     */
    public function __construct()
    {
        $this->setLoanAmount(3);
    }

    /**
     * Checks if the professor can make more loans
     * @param int $amountCurrentLoans Current number of loans
     * @return bool Returns true if the professor can make more loans
     */
    public function canTakeOutMoreLoans(int $amountCurrentLoans): bool
    {
        return $amountCurrentLoans <= $this->getLoanAmount();
    }

}
