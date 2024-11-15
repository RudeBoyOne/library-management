<?php
namespace Tests\Domain\Entities;

use App\Library\Domain\Entities\UserEntities\Student;
use App\Library\Domain\Entities\UserEntities\User;
use App\Library\Domain\Entities\Role;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\Covers;

#[CoversClass(Student::class)] 
#[CoversClass(User::class)] 
#[CoversClass(Role::class)]
class StudentTest extends TestCase
{

    #[Covers('Student::__construct')] 
    #[Covers('Student::getLoanAmount')]
    public function testStudentInheritsFromUser()
    {
        $student = new Student();
        $this->assertInstanceOf(User::class, $student);
    }

    #[Covers('Student::setName')] 
    #[Covers('Student::getName')]
    public function testSetNameAndGetName()
    {
        $student = new Student();
        $student->setName('John Doe');
        $this->assertEquals('John Doe', $student->getName());
    }

    #[Covers('Student::setEmail')] 
    #[Covers('Student::getEmail')]
    public function testSetEmailAndGetEmail()
    {
        $student = new Student();
        $student->setEmail('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $student->getEmail());
    }

    #[Covers('Student::setRole')] 
    #[Covers('Student::getRole')] 
    #[Covers('Role::__construct')]
    public function testSetRoleAndGetRole()
    {
        $role = new Role();
        $student = new Student();
        $student->setRole($role);
        $this->assertSame($role, $student->getRole());
    }

    #[Covers('Student::canTakeOutMoreLoans')]
    public function testConstructorSetsLoanAmount()
    {
        $student = new Student();
        $this->assertEquals(1, $student->getLoanAmount());
    }

    #[Covers('Student::__construct')] 
    #[Covers('Student::getLoanAmount')]
    public function testCanTakeOutMoreLoans()
    {
        $student = new Student();
        $this->assertTrue($student->canTakeOutMoreLoans(0));
        $this->assertTrue($student->canTakeOutMoreLoans(1));
        $this->assertFalse($student->canTakeOutMoreLoans(2));
    }

}
