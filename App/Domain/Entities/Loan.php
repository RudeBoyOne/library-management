<?php
namespace App\Library\Domain\Entities;

use App\Library\Domain\Entities\UserEntities\User;
use DateTime;
use InvalidArgumentException;

/**
 * Class Loan
 *
 * Represents a book loan
 */
class Loan
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
}
