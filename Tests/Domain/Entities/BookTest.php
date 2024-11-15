<?php
namespace Tests\Domain\Entities;

use App\Library\Domain\Entities\Book;
use App\Library\Domain\Entities\ISBN;
use App\Library\Domain\Entities\Section;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\Covers;

#[CoversClass(Book::class)] 
#[CoversClass(ISBN::class)] 
#[CoversClass(Section::class)]
class BookTest extends TestCase
{   
    #[Covers('Book::setTitle')] 
    #[Covers('Book::getTitle')]
    public function testGetAndSetTitle()
    {$book = new Book();
        $book->setTitle('The Great Gatsby');
        $this->assertEquals('The Great Gatsby', $book->getTitle());
    }
    
    #[Covers('Book::setAuthor')] 
    #[Covers('Book::getAuthor')]
    public function testGetAndSetAuthor()
    {
        $book = new Book();
        $book->setAuthor('F. Scott Fitzgerald');
        $this->assertEquals('F. Scott Fitzgerald', $book->getAuthor());
    }
    
    #[Covers('Book::setIsbn')] 
    #[Covers('Book::getIsbn')] 
    #[Covers('ISBN::setValue')] 
    #[Covers('ISBN::getValue')]
    public function testGetAndSetIsbn()
    {
        $isbn = new ISBN();
        $isbn->setValue('9783161484100');
        $book = new Book();
        $book->setIsbn($isbn);
        $this->assertSame($isbn, $book->getIsbn());
    }
    
    #[Covers('Book::setSection')] 
    #[Covers('Book::getSection')] 
    #[Covers('Section::__construct')]
    public function testGetAndSetSection()
    {
        $section = new Section();
        $book = new Book();
        $book->setSection($section);
        $this->assertSame($section, $book->getSection());
    }
    
    #[Covers('Book::setIsbn')] 
    #[Covers('Book::getIsbn')] 
    #[Covers('ISBN::equals')] 
    #[Covers('ISBN::setValue')] 
    #[Covers('ISBN::getValue')]
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
    
    #[Covers('Book::setIsbn')] 
    #[Covers('Book::getIsbn')] 
    #[Covers('ISBN::equals')] 
    #[Covers('ISBN::setValue')] 
    #[Covers('ISBN::getValue')]
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
