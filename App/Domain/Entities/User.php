<?php
namespace App\Library\Domain\Entities;

use App\Library\Domain\Entities\Role;

abstract class User
{
    /**
     * User id
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
     * user loan amount
     * @var int
     */
    private int $loanAmount;
    /**
     * Summary of role
     * @var Role
     */
    private Role $role;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return self
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Username
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Username
     * @param string $name Username
     * @return self
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * User email
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * User email
     * @param string $email User email
     * @return self
     */
    public function setEmail($email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * User registration
     * @return string
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * User registration
     * @param string $registration User registration
     * @return self
     */
    public function setRegistration($registration): self
    {
        $this->registration = $registration;
        return $this;
    }

    /**
     * User role
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * User role
     * @param Role $role User role
     * @return self
     */
    public function setRole(Role $role): self
    {
        $this->role = $role;
        return $this;
    }

    /**
     * user loan amount
     * @return int
     */
    public function getLoanAmount(): int
    {
        return $this->loanAmount;
    }

    /**
     * user loan amount
     * @param int $loanAmount user loan amount
     * @return self
     */
    public function setLoanAmount(int $loanAmount): self
    {
        $this->loanAmount = $loanAmount;
        return $this;
    }

    /**
     * The user can take out more loans
     * @param int $amountCurrentLoans
     * @return bool
     */
    abstract public function canTakeOutMoreLoans(int $amountCurrentLoans): bool;

}
