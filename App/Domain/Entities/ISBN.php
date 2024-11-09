<?php
namespace App\Library\Domain\Entities;

/**
 * Class ISBN
 * 
 * Represents the ISBN code of books in the application.
 * This class is a Value Object and is immutable.
 */
final class ISBN
{
    /**
     * The value of the ISBN
     * @var string
     */
    private string $value;

    /**
     * Gets the ISBN value
     * @return string The ISBN value
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Sets the ISBN value
     * @param string $value The new ISBN value
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Checks equality with another ISBN object
     * @param ISBN $isbn The ISBN object to compare with
     * @return bool Returns true if the ISBNs are equal.
     */
    public function equals(ISBN $isbn): bool
    {
        return $this->value === $isbn->getValue();
    }
}
