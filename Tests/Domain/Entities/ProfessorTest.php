<?php
namespace Tests\Domain\Entities;

use App\Library\Domain\Entities\UserEntities\Professor;
use App\Library\Domain\Entities\UserEntities\User;
use App\Library\Domain\Entities\Role;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\Covers;

#[CoversClass(Professor::class)] 
#[CoversClass(User::class)] 
#[CoversClass(Role::class)]
class ProfessorTest extends TestCase
{
    #[Covers('Professor::__construct')] 
    #[Covers('Professor::getLoanAmount')]
    public function testProfessorInheritsFromUser()
    {
        $professor = new Professor();
        $this->assertInstanceOf(User::class, $professor);
    }

    #[Covers('Professor::setName')] 
    #[Covers('Professor::getName')]
    public function testSetNameAndGetName()
    {
        $professor = new Professor();
        $professor->setName('John Doe');
        $this->assertEquals('John Doe', $professor->getName());
    }

    #[Covers('Professor::setEmail')] 
    #[Covers('Professor::getEmail')]
    public function testSetEmailAndGetEmail()
    {
        $professor = new Professor();
        $professor->setEmail('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $professor->getEmail());
    }

    #[Covers('Professor::setRole')] 
    #[Covers('Professor::getRole')] 
    #[Covers('Role::__construct')]
    public function testSetRoleAndGetRole()
    {
        $role = new Role();
        $professor = new Professor();
        $professor->setRole($role);
        $this->assertSame($role, $professor->getRole());
    }

    #[Covers('Professor::__construct')] 
    #[Covers('Professor::getLoanAmount')]
    public function testConstructorSetsLoanAmount()
    {
        $professor = new Professor();
        $this->assertEquals(3, $professor->getLoanAmount());
    }

    #[Covers('Professor::canTakeOutMoreLoans')]
    public function testCanTakeOutMoreLoans()
    {
        $professor = new Professor();
        $this->assertTrue($professor->canTakeOutMoreLoans(2));
        $this->assertTrue($professor->canTakeOutMoreLoans(3));
        $this->assertFalse($professor->canTakeOutMoreLoans(4));
    }
}
