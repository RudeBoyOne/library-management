<?php
namespace App\Library\Domain\Services;

use App\Library\Domain\Entities\Loan;
use App\Library\Domain\Exceptions\BookNotAvailableException;
use App\Library\Domain\Exceptions\ExceedsLoanLimitException;
use App\Library\Domain\Repositorys\BookRepository;
use App\Library\Domain\Repositorys\LoanRepository;
use App\Library\Domain\Repositorys\UserRepository;
use App\Library\Util\DateTimeZoneCustom;
use Exception;

class LoanService
{
    private LoanRepository $loanRepository;
    private UserRepository $userRepository;
    private BookRepository $bookRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
        $this->userRepository = new UserRepository();
        $this->bookRepository = new BookRepository();
    }

    public function create($data): bool
    {
        $user = $this->userRepository->getUserById($data->user);
        $books = $this->assemblerArrayBookEntities($data->books);

        
        $howManyLoansDoesThisUserHave = $this->loanRepository->howManyLoansDoesAUserHave($user->getId());
        
        $roleName = $user->getRole()->getName();
        
        switch ($roleName) {
            case 'Professor':

                if ($howManyLoansDoesThisUserHave == $user->getLoanAmount()) {
                    throw new ExceedsLoanLimitException('Professor excedeu o limite de empréstimos.');
                }
                
                break;
                
                case 'Student':
                    
                    if ($howManyLoansDoesThisUserHave == $user->getLoanAmount()) {
                    throw new ExceedsLoanLimitException('Student excedeu o limite de empréstimos.');
                }

                break;
        }

        $loan = new Loan();
        $loan->setDateLoan(DateTimeZoneCustom::stringToDateTimeConverter($data->dateLoan))
        ->setReturnLoan(DateTimeZoneCustom::stringToDateTimeConverter($data->returnLoan))
        ->setUser($user)
        ->setBooks($books);

        return $this->loanRepository->createLoan($loan);
    }

    

    private function assemblerArrayBookEntities(array $idBooks)
    {   
        $books = array();
        foreach ($idBooks as $idBook) {

            $book = $this->bookRepository->getBookById($idBook);

            
            $numberOfCopiesBorrowed = $this->loanRepository->howManyCopiesOfABookAreOnLoan($idBook);
            
            $numberOfCopiesOfTheBook = $book->getAmountOfBooks();
            
            if ($numberOfCopiesBorrowed >= $numberOfCopiesOfTheBook) {
                throw new BookNotAvailableException("Não há exemplares do livro ". $book->getTitle() ." disponíveis!");
            }

            array_push($books, $book);
        }
        return $books;
    }
}
