<?php
namespace App\Library\Domain\Entities;

use JsonSerializable;

/**
 * Class Role
 *
 * Represents a user role in the system
 */
class Role implements JsonSerializable
{
    /**
     * Role ID
     * @var int
     */
    private int $id;
    /**
     * Role name
     * @var string
     */
    private string $name;

    /**
     * Gets the role ID
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the role ID
     * @param int $id Role ID
     * @return self
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the role name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the role name
     * @param string $name Role name
     * @return self
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }
    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            "name" => $this->getName()
        ];
    }
}
