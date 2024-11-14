<?php
namespace App\Library\Domain\Repositorys\Implementation;

use App\Library\Domain\Entities\Section;
use App\Library\Domain\Repositorys\SectionRepository;
use App\Library\Infrastructure\Persistence\Connection;
use PDO;

class SectionRepositoryImpl implements SectionRepository
{
    private PDO $connection;
    private string $table = "section";

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function getSectionById(int $idSection): ?Section
    {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $idSection, PDO::PARAM_INT);
        $statement->execute();
        $section = $statement->fetch(PDO::FETCH_OBJ);

        return $this->assemblerSection($section);
    }

    public function assemblerSection($section)
    {
        $sectionEntitie = new Section();
        $sectionEntitie->setDescription($section->description)
                       ->setLocalizator($section->localization)
                       ->setId($section->id);

        return $sectionEntitie;
    }
}
