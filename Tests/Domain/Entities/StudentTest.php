<?php
namespace Tests\Domain\Entities;

use App\Library\Domain\Entities\Role;
use App\Library\Domain\Entities\Student;
use App\Library\Domain\Entities\User;
use PHPUnit\Framework\TestCase;

class StudentTest extends TestCase
{

    public function testStudentInheritsFromUser()
    {
        $student = new Student();
        $this->assertInstanceOf(User::class, $student);
    }

    public function testSetNameAndGetName()
    {
        $student = new Student();
        $student->setName('John Doe');
        $this->assertEquals('John Doe', $student->getName());
    }

    public function testSetEmailAndGetEmail()
    {
        $student = new Student();
        $student->setEmail('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $student->getEmail());
    }

    public function testSetRoleAndGetRole()
    {
        $role = new Role();
        $student = new Student();
        $student->setRole($role);
        $this->assertSame($role, $student->getRole());
    }

    public function testConstructorSetsLoanAmount()
    {
        $student = new Student();
        $this->assertEquals(1, $student->getLoanAmount());
    }

    public function testCanTakeOutMoreLoans()
    {
        $student = new Student();
        $this->assertTrue($student->canTakeOutMoreLoans(0));
        $this->assertTrue($student->canTakeOutMoreLoans(1));
        $this->assertFalse($student->canTakeOutMoreLoans(2));
    }

}
