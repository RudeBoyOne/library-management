<?php
namespace App\Library\Domain\Entities;

use App\Library\Domain\Entities\ISBN;
use App\Library\Domain\Entities\Section;
use JsonSerializable;

/**
 * Class Book
 *
 * Represents a book in the library
 */
class Book implements JsonSerializable
{
    /**
     * Book id
     * @var int
     */
    private int $id;
    /**
     * Book title
     * @var string
     */
    private string $title;
    /**
     * Book author
     * @var string
     */
    private string $author;
    /**
     * Book ISBN
     * @var ISBN
     */
    private ISBN $isbn;
    /**
     * Amount Of Books
     * @var int
     */
    private int $amountOfBooks;
    /**
     * Book section
     * @var Section
     */
    private Section $section;

    /**
     * Gets the book ID
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the book ID
     * @param int $id Book ID
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the book title
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the book title
     * @param string $title Book title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Gets the book author
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Sets the book author
     * @param string $author Book author
     * @return self
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Gets the book ISBN
     * @return ISBN
     */
    public function getIsbn(): ISBN
    {
        return $this->isbn;
    }

    /**
     * Sets the book ISBN
     * @param ISBN $isbn Book ISBN
     * @return self
     */
    public function setIsbn(ISBN $isbn): self
    {
        $this->isbn = $isbn;
        return $this;
    }

    /**
     * Gets the book section
     * @return Section
     */
    public function getSection(): Section
    {
        return $this->section;
    }

    /**
     * Sets the book section
     * @param Section $section Book section
     * @return self
     */
    public function setSection(Section $section): self
    {
        $this->section = $section;
        return $this;
    }

    /**
     * Gets the Amount Of Books
     * @return int
     */
    public function getAmountOfBooks(): int
    {
        return $this->amountOfBooks;
    }

    /**
     * Sets the Amount Of Books
     * @param int $amountOfBooks Amount Of Books
     * @return self
     */
    public function setAmountOfBooks(int $amountOfBooks): self
    {
        $this->amountOfBooks = $amountOfBooks;
        return $this;
    }
    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "title"=> $this->getTitle(),
            "author"=> $this->getAuthor(),
            "isbn"=> $this->getIsbn(),
            "section"=> $this->getSection()
        ];
    }
}
