<?php
namespace App\Library\Domain\Entities;

class Role
{
    /**
     * Summary of id
     * @var int
     */
    private $id;
    /**
     * Summary of name
     * @var string
     */
    private $name;

	/**
	 * Summary of id
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Summary of id
	 * @param int $id Summary of id
	 * @return self
	 */
	public function setId($id): self {
		$this->id = $id;
		return $this;
	}

	/**
	 * Summary of name
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Summary of name
	 * @param string $name Summary of name
	 * @return self
	 */
	public function setName($name): self {
		$this->name = $name;
		return $this;
	}
}