<?php
namespace App\Library\Domain\Entities;

use App\Library\Domain\Entities\Role;

/**
 * Class User
 * 
 * Represents a user in the system. 
 */
abstract class User
{
    /**
     * User ID
     * @var int
     */
    private int $id;
    /**
     * Username
     * @var string
     */
    private string $name;
    /**
     * User email
     * @var string
     */
    private string $email;
    /**
     * User registration
     * @var string
     */
    private string $registration;
    /**
     * Number of loans the user can make
     * @var int
     */
    private int $loanAmount;
    /**
     * User role
     * @var Role
     */
    private Role $role;

    /**
     * Gets the user ID
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the user ID
     * @param int $id User ID
     * @return self
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the username
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the username
     * @param string $name Username
     * @return self
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the user email
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the user email
     * @param string $email User email
     * @return self
     */
    public function setEmail($email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Gets the user registration
     * @return string
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * Sets the user registration
     * @param string $registration User registration
     * @return self
     */
    public function setRegistration($registration): self
    {
        $this->registration = $registration;
        return $this;
    }

    /**
     * Gets the user role
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * Sets the user role
     * @param Role $role User role
     * @return self
     */
    public function setRole(Role $role): self
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Gets the number of loans the user can make
     * @return int
     */
    public function getLoanAmount(): int
    {
        return $this->loanAmount;
    }

    /**
     * Sets the number of loans the user can make
     * @param int $loanAmount Number of loans
     * @return self
     */
    public function setLoanAmount(int $loanAmount): self
    {
        $this->loanAmount = $loanAmount;
        return $this;
    }

    /**
     * Checks if the user can make more loans
     * @param int $amountCurrentLoans Current number of loans
     * @return bool Returns true if the user can make more loans.
     */
    abstract public function canTakeOutMoreLoans(int $amountCurrentLoans): bool;

}
