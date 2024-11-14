<?php
namespace App\Library\Domain\Repositorys;

use App\Library\Domain\Entities\Loan;

interface LoanRepository
{
    public function createLoan(Loan $loan): bool;
    public function updateLoan(Loan $loan): bool;
    public function getLoanById(int $idLoan): Loan;
    public function toPickUpBooksFromALoan(int $idLoan): array;
    public function getAllLoans() : array;
    public function deleteLoan(int $idLoan): bool;
    public function howManyCopiesOfABookAreOnLoan(int $idBook): int;
    public function howManyLoansDoesAUserHave(int $idUser): int;
}
