<?php
namespace App\Library\Domain\Repositorys;

use App\Library\Domain\Entities\Section;

/**
 * Interface SectionRepository
 * 
 * Defines the contract for section repository operations. 
 */
interface SectionRepository
{
    /**
     * Retrieves a section by its ID.
     * 
     * @param int $idSection The ID of the section to retrieve.
     * @return Section|null Returns the section if found, null otherwise. 
     * 
     * */
    public function getSectionById(int $idSection): ?Section;
}
