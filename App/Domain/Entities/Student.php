<?php
namespace App\Library\Domain\Entities;

use App\Library\Domain\Entities\User;

class Student extends User
{
    
    /**
     */
    public function __construct() {
        $this->setLoanAmount(1);
    }
    
    /**
     * @inheritDoc
     */
    public function canTakeOutMoreLoans(int $amountCurrentLoans): bool {
        return $amountCurrentLoans <= $this->getLoanAmount();
    }

}