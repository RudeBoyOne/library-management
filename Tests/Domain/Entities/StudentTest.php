<?php
namespace Tests\Domain\Entities;

use App\Library\Domain\Entities\Student;
use PHPUnit\Framework\TestCase;

class StudentTest extends TestCase
{

    public function testCanTakeOutMoreLoansStudent()
    {
        $student = new Student();
        $this->assertIsBool($student->canTakeOutMoreLoans(1));
    }

}
