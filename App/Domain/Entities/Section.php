<?php
namespace App\Library\Domain\Entities;

use JsonSerializable;

/**
 * Class Section
 *
 * Represents a section in the library
 */
class Section implements JsonSerializable
{
    /**
     * Section ID
     * @var int
     */
    private int $id;
    /**
     * Section localizator
     * @var string
     */
    private string $localizator;
    /**
     * Section description
     * @var string
     */
    private string $description;

    /**
     * Gets the section ID
     * @return int The section ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the section ID
     * @param int $id The section ID
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the section localizator
     * @return string The section localizator
     */
    public function getLocalizator(): string
    {
        return $this->localizator;
    }

    /**
     * Sets the section localizator
     * @param string $localizator The section localizator
     * @return self
     */
    public function setLocalizator(string $localizator): self
    {
        $this->localizator = $localizator;
        return $this;
    }

    /**
     * Gets the section description
     * @return string The section description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the section description
     * @param string $description The section description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            "localizator"=> $this->getLocalizator(),
            "description"=> $this->getDescription()
        ];
    }
}
