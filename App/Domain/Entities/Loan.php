<?php
namespace App\Library\Domain\Entities;

use App\Library\Domain\Entities\UserEntities\User;
use App\Library\Util\DateTimeZoneCustom;
use DateTime;
use InvalidArgumentException;
use JsonSerializable;

/**
 * Class Loan
 *
 * Represents a book loan
 */
class Loan implements JsonSerializable
{
    /**
     * Loan ID
     * @var int
     */
    private int $id;
    /**
     * Loan date
     * @var DateTime
     */
    private DateTime $dateLoan;
    /**
     * Return date
     * @var DateTime
     */
    private DateTime $returnLoan;
    /**
     * User who made the loan
     * @var User
     */
    private User $user;
    /**
     * Books from a loan
     * @var Book[]
     * */
    private array $books = [];

    /**
     * Gets the loan ID
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the loan ID
     * @param int $id Loan ID
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the loan date
     * @return DateTime
     */
    public function getDateLoan(): DateTime
    {
        return $this->dateLoan;
    }

    /**
     * Sets the loan date
     * @param DateTime $dateLoan Loan date
     * @return self
     */
    public function setDateLoan(DateTime $dateLoan): self
    {
        $this->dateLoan = $dateLoan;
        return $this;
    }

    /**
     * Gets the return date
     * @return DateTime
     */
    public function getReturnLoan(): DateTime
    {
        return $this->returnLoan;
    }

    /**
    /**
     * Sets the return date
     *
     * Ensures that the return date is after the loan date
     * Throws an InvalidArgumentException if the return date is not after the loan date
     * @param DateTime $returnLoan Return date
     * @return self
     * @throws InvalidArgumentException if the return date is not after the loan date.
     */
    public function setReturnLoan(DateTime $returnLoan): self
    {
        if ($returnLoan <= $this->dateLoan) {
            throw new InvalidArgumentException("Return date must be after loan date");
        }

        $this->returnLoan = $returnLoan;
        return $this;
    }

    /**
     * Gets user who made the loan
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Sets user who made the loan
     * @param User $user Return User
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Gets books from a loan
     * @return array
     */
    public function getBooks(): array
    {
        return $this->books;
    }

    /**
     * Add a book to loan
     * @param array $books
     * @return self
     */
    public function addBooks(Book $book): self
    {
        $this->books[] = $book;
        return $this;
    }

    /**
     * Books from a loan
     * @param array $books Books from a loan
     * @return self
     */
    public function setBooks(array $books): self
    {
        $this->books = $books;
        return $this;
    }
    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            "id_loan"=> $this->getId(),
            "date_loan"=> DateTimeZoneCustom::dateTimeToStringConverterWithoutSeconds($this->getDateLoan()),
            "date_return_loan"=> DateTimeZoneCustom::dateTimeToStringConverterWithoutSeconds($this->getReturnLoan()),
            "user"=> $this->getUser(),
            "books"=> $this->getBooks(),
        ];
    }
}
