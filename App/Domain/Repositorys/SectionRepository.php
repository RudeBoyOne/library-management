<?php
namespace App\Library\Domain\Repositorys;

use App\Library\Domain\Entities\Section;

interface SectionRepository
{
    public function getSectionById(int $idSection): ?Section;
}
