<?php
namespace App\Library\Domain\Services;

use App\Library\Domain\Entities\Section;
use App\Library\Domain\Repositorys\SectionRepository;

/**
 * Class SectionService
 *
 * Provides services for managing sections.
 */
class SectionService
{
    private SectionRepository $sectionRepository;

    /**
     * Constructor for the SectionService class.
     *
     * Initializes the section repository.
     *
     * @param SectionRepository $sectionRepository The repository for managing sections.
     */
    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    /**
     * Retrieves a section by its ID.
     *
     * @param int $idSection The ID of the section to retrieve.
     * @return Section Returns the section if found.
     */
    public function getSectionById(int $idSection): Section
    {
        $secttion = $this->sectionRepository->getSectionById($idSection);

        return $secttion;
    }
}
