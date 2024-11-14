<?php
namespace App\Library\Domain\Services;

use App\Library\Domain\Entities\Section;
use App\Library\Domain\Repositorys\SectionRepository;

class SectionService
{
    private SectionRepository $sectionRepository;

    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function getSectionById(int $idSection): Section
    {
        $secttion = $this->sectionRepository->getSectionById($idSection);

        return $secttion;
    }
}
