<?php
namespace App\Library\Domain\Repositorys\Implementation;

use App\Library\Domain\Entities\Section;
use App\Library\Domain\Repositorys\SectionRepository;
use App\Library\Infrastructure\Persistence\Connection;
use PDO;

/**
 * Class SectionRepositoryImpl
 * 
 * Implements the SectionRepository interface using a PDO connection. 
 */
class SectionRepositoryImpl implements SectionRepository
{
    private PDO $connection;
    private string $table = "section";

    /** 
     * Constructor for the SectionRepositoryImpl class. 
     * Initializes the database connection. 
     * 
     * */
    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    /**
     * Retrieves a section by its ID.
     * 
     * @param int $idSection The ID of the section to retrieve.
     * @return Section|null Returns the section if found, null otherwise. 
     * */
    public function getSectionById(int $idSection): ?Section
    {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $idSection, PDO::PARAM_INT);
        $statement->execute();
        $section = $statement->fetch(PDO::FETCH_OBJ);

        return $this->assemblerSection($section);
    }

    /** 
     * Assembles a Section entity from a database result.
     * 
     * @param object $section The database result. 
     * @return Section The assembled Section entity. 
     * 
     */
    public function assemblerSection($section)
    {
        $sectionEntitie = new Section();
        $sectionEntitie->setDescription($section->description)
                       ->setLocalizator($section->localization)
                       ->setId($section->id);

        return $sectionEntitie;
    }
}
