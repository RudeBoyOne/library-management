<?php
namespace App\Library\Domain\Services;

use App\Library\Domain\Entities\Loan;
use App\Library\Domain\Entities\UserEntities\User;
use App\Library\Domain\Exceptions\BookNotAvailableException;
use App\Library\Domain\Exceptions\ExceedsLoanLimitException;
use App\Library\Domain\Repositorys\BookRepository;
use App\Library\Domain\Repositorys\Implementation\BookRepositoryImpl;
use App\Library\Domain\Repositorys\LoanRepository;
use App\Library\Domain\Repositorys\UserRepository;
use App\Library\Util\DateTimeZoneCustom;

class LoanService
{
    private LoanRepository $loanRepository;
    private UserRepository $userRepository;
    private BookRepository $bookRepository;
    private int $howManyLoansDoesThisUserHave;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
        $this->userRepository = new UserRepository();
        $this->bookRepository = new BookRepositoryImpl();
    }

    public function create($data): bool
    {
        $user = $this->userRepository->getUserById($data->user);
        $books = $this->assemblerArrayBookEntities($data->books);

        $this->howManyLoansDoesThisUserHave = $this->loanRepository->howManyLoansDoesAUserHave($user->getId());

        $roleName = $user->getRole()->getName();

        $this->userTypeChecker($roleName, $user->getLoanAmount());

        $loan = $this->assemblerLoanEntitie($data, $user, $books);

        return $this->loanRepository->createLoan($loan);
    }

    public function update(int $idLoan, $data): bool
    {
        $user = $this->userRepository->getUserById($data->user);
        $books = $this->assemblerArrayBookEntities($data->books);

        $this->howManyLoansDoesThisUserHave = $this->loanRepository->howManyLoansDoesAUserHave($user->getId());

        $roleName = $user->getRole()->getName();

        $this->userTypeChecker($roleName, $user->getLoanAmount());

        $loan = $this->assemblerLoanEntitie($data, $user, $books);

        $loan->setId($idLoan);

        return $this->loanRepository->updateLoan($loan);
    }

    public function getLoanById(int $idLoan): Loan
    {
        return $this->loanRepository->getLoanById($idLoan);
    }

    public function getAllLoans(): array
    {
        return $this->loanRepository->getAllLoans();
    }

    public function delete(int $idLoan): bool
    {
        return $this->loanRepository->deleteLoan($idLoan);
    }

    private function userTypeChecker(string $roleName, int $userLoanAmount)
    {
        switch ($roleName) {
            case 'Professor':

                if ($this->howManyLoansDoesThisUserHave == $userLoanAmount) {
                    throw new ExceedsLoanLimitException('Professor excedeu o limite de empréstimos.');
                }

                break;

            case 'Student':

                if ($this->howManyLoansDoesThisUserHave == $userLoanAmount) {
                    throw new ExceedsLoanLimitException('Student excedeu o limite de empréstimos.');
                }

                break;
        }

    }

    private function assemblerLoanEntitie($data, User $user, array $books): Loan
    {
        $loan = new Loan();
        $loan->setDateLoan(DateTimeZoneCustom::stringToDateTimeConverter($data->dateLoan))
            ->setReturnLoan(DateTimeZoneCustom::stringToDateTimeConverter($data->returnLoan))
            ->setUser($user)
            ->setBooks($books);

        return $loan;
    }

    private function assemblerArrayBookEntities(array $idBooks)
    {
        $books = array();
        foreach ($idBooks as $idBook) {

            $book = $this->bookRepository->getBookById($idBook);

            $numberOfCopiesBorrowed = $this->loanRepository->howManyCopiesOfABookAreOnLoan($idBook);

            $numberOfCopiesOfTheBook = $book->getAmountOfBooks();

            if ($numberOfCopiesBorrowed >= $numberOfCopiesOfTheBook) {
                throw new BookNotAvailableException("Não há exemplares do livro " . $book->getTitle() . " disponíveis!");
            }

            array_push($books, $book);
        }
        return $books;
    }
}
