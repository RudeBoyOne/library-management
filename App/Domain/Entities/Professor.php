<?php
namespace App\Library\Domain\Entities;

use App\Library\Domain\Entities\User;

/**
 * Summary of Professor
 */
class Professor extends User
{

    /**
     * método construtor de Professor um usuário que a ele é permitido fazer até 3 empréstimos
     */
    public function __construct()
    {
        $this->setLoanAmount(3);
    }

    /**
     * @inheritDoc
     */
    public function canTakeOutMoreLoans(int $amountCurrentLoans): bool
    {
        return $amountCurrentLoans <= $this->getLoanAmount();
    }

}
