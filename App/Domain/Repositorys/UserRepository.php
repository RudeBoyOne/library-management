<?php
namespace App\Library\Domain\Repositorys;

use App\Library\Domain\Entities\Role;
use App\Library\Domain\Entities\UserEntities\Professor;
use App\Library\Domain\Entities\UserEntities\Student;
use App\Library\Domain\Entities\UserEntities\User;
use App\Library\Infrastructure\Persistence\Connection;
use PDO;

class UserRepository
{
    private PDO $connection;
    private string $table = 'user';
    private string $tableAssoc = 'role';

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function createUser(User $user): bool
    {
        $name = $user->getName();
        $email = $user->getEmail();
        $registration = $user->getRegistration();
        $role = $user->getRole()->getId();

        $query = "INSERT INTO $this->table (name, email, registration, role) VALUES (:name, :email, :registration, :role)";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(":name", $name, PDO::PARAM_STR);
        $statement->bindParam(":email", $email, PDO::PARAM_STR);
        $statement->bindParam(":registration", $registration, PDO::PARAM_STR);
        $statement->bindParam(":role", $role, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function updateUser(int $idUser, User $user): bool
    {
        $name = $user->getName();
        $email = $user->getEmail();
        $registration = $user->getRegistration();
        $role = $user->getRole()->getId();

        $query = "UPDATE $this->table SET name = :name, email = :email, registration = :registration, role = :role WHERE id = :id";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(":name", $name, PDO::PARAM_STR);
        $statement->bindParam(":email", $email, PDO::PARAM_STR);
        $statement->bindParam(":registration", $registration, PDO::PARAM_STR);
        $statement->bindParam(":role", $role, PDO::PARAM_INT);
        $statement->bindParam(":id", $idUser, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function getUserById(int $idUser): ?User
    {
        $query = "SELECT u.*, r.name as role_name FROM $this->table u JOIN $this->tableAssoc r ON u.role = r.id WHERE u.id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $idUser, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_OBJ);

        if (!$result) {
            return null;
        }

        switch ($result->role_name) {
            case 'Professor':
                $professor = new Professor();
                $professor->setId($idUser)
                    ->setName($result->name)
                    ->setEmail($result->email)
                    ->setRegistration($result->registration);

                $role = new Role();
                $role->setId($result->role)
                    ->setName($result->role_name);

                $professor->setRole($role);

                return $professor;

            case 'Student':
                $student = new Student();
                $student->setId($idUser)
                    ->setName($result->student_name)
                    ->setEmail($result->student_email)
                    ->setRegistration($result->registration);

                $role = new Role();
                $role->setId($result->role)
                    ->setName($result->role_name);

                $student->setRole($role);

                return $student;
            default:
                throw new \InvalidArgumentException("Unknown role name");
        }
    }

    public function getAllUsers(): array
    {
        $query = "SELECT * FROM $this->table";
        $statement = $this->connection->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($idUser): bool
    {
        $query = "DELETE FROM $this->table WHERE id = :id";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $idUser, PDO::PARAM_INT);
        
        return $statement->execute();
    }
}
