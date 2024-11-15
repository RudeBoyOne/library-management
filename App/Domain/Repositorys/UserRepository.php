<?php
namespace App\Library\Domain\Repositorys;

use App\Library\Domain\Entities\Role;
use App\Library\Domain\Entities\UserEntities\Professor;
use App\Library\Domain\Entities\UserEntities\Student;
use App\Library\Domain\Entities\UserEntities\User;
use App\Library\Infrastructure\Persistence\Connection;
use InvalidArgumentException;
use PDO;

/** 
 * Class UserRepository
 * 
 * This class manages the persistence of users in the database. 
 */
class UserRepository
{
    private PDO $connection;
    private string $table = 'user';
    private string $tableAssoc = 'role';

    /**
     * Constructor for the UserRepository class.
     * 
     * Initializes the database connection. 
     */
    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    /** 
     * Creates a new user in the database.
     * 
     * @param User $user The user to be created.
     * @return bool Returns true if the user was successfully created, false otherwise. 
     */
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

    /**
     * Updates an existing user in the database.
     * 
     * @param User $user The user to be updated.
     * @return bool Returns true if the user was successfully updated, false otherwise. 
     * 
     */
    public function updateUser(User $user): bool
    {
        $id = $user->getId();
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
        $statement->bindParam(":id", $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * Retrieves a user from the database by their ID.
     * 
     * @param int $idUser The ID of the user to be retrieved.
     * @return User|null Returns the user found or null if not found.
     * @throws InvalidArgumentException If the user's role name is unknown. 
     */
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
                    ->setName($result->name)
                    ->setEmail($result->email)
                    ->setRegistration($result->registration);

                $role = new Role();
                $role->setId($result->role)
                    ->setName($result->role_name);

                $student->setRole($role);

                return $student;
            default:
                throw new InvalidArgumentException("Unknown role name");
        }
    }

    /**
     * Retrieves all users from the database.
     * 
     * @return array Returns an array of all users found. 
     */
    public function getAllUsers(): array
    {
        $query = "SELECT * FROM $this->table";
        $statement = $this->connection->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Deletes a user from the database by their ID.
     * @param int $idUser The ID of the user to be deleted.
     * @return bool Returns true if the user was successfully deleted, false otherwise. 
     * 
     */
    public function deleteUser($idUser): bool
    {
        $query = "DELETE FROM $this->table WHERE id = :id";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(":id", $idUser, PDO::PARAM_INT);

        return $statement->execute();
    }
}
