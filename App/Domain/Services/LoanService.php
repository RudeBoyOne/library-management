<?php
namespace App\Library\Domain\Services;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\Loan;
use App\Library\Domain\Entities\UserEntities\Professor;
use App\Library\Domain\Repositorys\LoanRepository;

class LoanService
{
    private LoanRepository $loanRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function create($data): bool
    {
        $user = new Professor();
        $user->setId($data->user);
        $books = $this->assemblerArrayBookEntities($data->book);
        $loan = new Loan();
        $loan->setDateLoan($data->dateLoan)
            ->setReturnLoan($data->returnLoan)
            ->setUser($user)
            ->setBooks($books);

        return $this->loanRepository->createLoan($loan);
    }

    

    private function assemblerArrayBookEntities(array $bookIds)
    {   
        $books = array();
        foreach ($bookIds as $bookId) {
            $book = new Book();
            $book->setId($bookId);
            array_push($books, $book);
        }
        return $books;
    }
}
