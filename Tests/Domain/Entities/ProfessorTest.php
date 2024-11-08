<?php
namespace Tests\Domain\Entities;

use App\Library\Domain\Entities\Professor;
use PHPUnit\Framework\TestCase;

class ProfessorTest extends TestCase
{
    public function testCanTakeOutMoreLoans() {
        $professor = new Professor();
        $this->assertIsBool($professor->canTakeOutMoreLoans(2));
    }
}