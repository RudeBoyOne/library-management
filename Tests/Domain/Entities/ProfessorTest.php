<?php
namespace Tests\Domain\Entities;

use App\Library\Domain\Entities\Professor;
use App\Library\Domain\Entities\Role;
use App\Library\Domain\Entities\User;
use PHPUnit\Framework\TestCase;

class ProfessorTest extends TestCase
{
    public function testProfessorInheritsFromUser()
    {
        $professor = new Professor();
        $this->assertInstanceOf(User::class, $professor);
    }

    public function testSetNameAndGetName()
    {
        $professor = new Professor();
        $professor->setName('John Doe');
        $this->assertEquals('John Doe', $professor->getName());
    }

    public function testSetEmailAndGetEmail()
    {
        $professor = new Professor();
        $professor->setEmail('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $professor->getEmail());
    }

    public function testSetRoleAndGetRole()
    {
        $role = new Role();
        $professor = new Professor();
        $professor->setRole($role);
        $this->assertSame($role, $professor->getRole());
    }

    public function testConstructorSetsLoanAmount()
    {
        $professor = new Professor();
        $this->assertEquals(3, $professor->getLoanAmount());
    }

    public function testCanTakeOutMoreLoans()
    {
        $professor = new Professor();
        $this->assertTrue($professor->canTakeOutMoreLoans(2));
        $this->assertTrue($professor->canTakeOutMoreLoans(3));
        $this->assertFalse($professor->canTakeOutMoreLoans(4));
    }
}
