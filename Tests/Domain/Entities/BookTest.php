<?php
namespace Tests\Domain\Entities;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\ISBN;
use App\Library\Domain\Entities\Section;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{   
    public function testGetAndSetTitle()
    {$book = new Book();
        $book->setTitle('The Great Gatsby');
        $this->assertEquals('The Great Gatsby', $book->getTitle());
    }
    
    public function testGetAndSetAuthor()
    {
        $book = new Book();
        $book->setAuthor('F. Scott Fitzgerald');
        $this->assertEquals('F. Scott Fitzgerald', $book->getAuthor());
    }
    
    public function testGetAndSetIsbn()
    {
        $isbn = new ISBN();
        $isbn->setValue('9783161484100');
        $book = new Book();
        $book->setIsbn($isbn);
        $this->assertSame($isbn, $book->getIsbn());
    }
    
    public function testGetAndSetSection()
    {
        $section = new Section();
        $book = new Book();
        $book->setSection($section);
        $this->assertSame($section, $book->getSection());
    }
    
    public function testBooksWithSameIsbnAreEqual()
    {
        $isbn1 = new ISBN();
        $isbn1->setValue('9783161484100');
        $isbn2 = new ISBN();
        $isbn2->setValue('9783161484100');
        $book1 = new Book();
        $book1->setIsbn($isbn1);
        $book2 = new Book();
        $book2->setIsbn($isbn2);
        $this->assertTrue($book1->getIsbn()->equals($book2->getIsbn()));
    }
    
    public function testBooksWithDifferentIsbnAreNotEqual()
    {
        $isbn1 = new ISBN();
        $isbn1->setValue('9783161484100');
        $isbn2 = new ISBN();
        $isbn2->setValue('9781402894626');
        $book1 = new Book();
        $book1->setIsbn($isbn1);
        $book2 = new Book();
        $book2->setIsbn($isbn2);
        $this->assertFalse($book1->getIsbn()->equals($book2->getIsbn()));
    }
}
