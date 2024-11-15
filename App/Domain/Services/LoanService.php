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

/**
 * Class LoanService
 *
 * Provides services for managing loans.
 */
class LoanService
{
    private LoanRepository $loanRepository;
    private UserRepository $userRepository;
    private BookRepository $bookRepository;
    private int $howManyLoansDoesThisUserHave;

    /**
     * Constructor for the LoanService class.
     *
     * Initializes the loan repository, user repository, and book repository.
     *
     * @param LoanRepository $loanRepository The repository for managing loans.
     */
    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
        $this->userRepository = new UserRepository();
        $this->bookRepository = new BookRepositoryImpl();
    }

    /**
     * Creates a new loan.
     *
     * @param object $data The data for creating the loan.
     * @return bool Returns true if the loan was successfully created, false otherwise.
     * @throws ExceedsLoanLimitException If the user exceeds the loan limit.
     * @throws BookNotAvailableException If the book is not available.
     */
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

    /**
     * Updates an existing loan.
     *
     * @param int $idLoan The ID of the loan to be updated.
     * @param object $data The data for updating the loan.
     * @return bool Returns true if the loan was successfully updated, false otherwise.
     * @throws ExceedsLoanLimitException If the user exceeds the loan limit.
     * @throws BookNotAvailableException If the book is not available.
     */
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

    /**
     * Retrieves a loan by its ID.
     *
     * @param int $idLoan The ID of the loan to retrieve.
     * @return Loan Returns the loan if found.
     */
    public function getLoanById(int $idLoan): Loan
    {
        return $this->loanRepository->getLoanById($idLoan);
    }

    /**
     * Retrieves all loans.
     *
     * @return array Returns an array of all loans.
     */
    public function getAllLoans(): array
    {
        return $this->loanRepository->getAllLoans();
    }

    /**
     * Deletes a loan by its ID.
     *
     * @param int $idLoan The ID of the loan to delete.
     * @return bool Returns true if the loan was successfully deleted, false otherwise.
     */
    public function delete(int $idLoan): bool
    {
        return $this->loanRepository->deleteLoan($idLoan);
    }

    /**
     * Checks the user's role type and loan amount.
     *
     * @param string $roleName The name of the user's role.
     * @param int $userLoanAmount The loan amount of the user.
     * @throws ExceedsLoanLimitException If the user exceeds the loan limit.
     */
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

    /**
     * Assembles a Loan entity from provided data, user, and books.
     *
     * @param object $data The data for the loan.
     * @param User $user The user associated with the loan.
     * @param array $books The books associated with the loan.
     * @return Loan The assembled Loan entity.
     */
    private function assemblerLoanEntitie($data, User $user, array $books): Loan
    {
        $loan = new Loan();
        $loan->setDateLoan(DateTimeZoneCustom::stringToDateTimeConverter($data->dateLoan))
            ->setReturnLoan(DateTimeZoneCustom::stringToDateTimeConverter($data->returnLoan))
            ->setUser($user)
            ->setBooks($books);

        return $loan;
    }

    /**
     * Assembles an array of Book entities from provided book IDs.
     *
     * @param array $idBooks The IDs of the books.
     * @return array The assembled array of Book entities.
     * @throws BookNotAvailableException If a book is not available.
     */
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
