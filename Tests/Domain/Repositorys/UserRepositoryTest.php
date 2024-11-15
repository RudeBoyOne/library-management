<?php
namespace Tests\Domain\Repositorys;

use App\Library\Domain\Entities\Role;
use App\Library\Domain\Entities\UserEntities\Professor;
use App\Library\Domain\Entities\UserEntities\User;
use App\Library\Domain\Repositorys\UserRepository;
use App\Library\Infrastructure\Persistence\Connection;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Metadata\Covers;
use Tests\SetupTests;

#[CoversClass(UserRepository::class)]
#[CoversClass(User::class)]
#[CoversClass(Professor::class)]
#[CoversClass(Role::class)]
#[CoversClass(Connection::class)]
class UserRepositoryTest extends SetupTests
{
    private UserRepository $userRepository;
    private string $table = 'user';
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userRepository = new UserRepository();

        $this->insertSampleData();
    }
    
    #[Covers('UserRepository::createUser')] 
    #[Covers('Role::getId')] 
    #[Covers('User::getName')] 
    #[Covers('User::getEmail')] 
    #[Covers('User::getRegistration')] 
    #[Covers('User::getRole')]
    public function testCreateUser(): void
    {
        $role = $this->createMock(Role::class);
        $role->method('getId')->willReturn(1);

        $user = $this->createMock(User::class);
        $user->method('getName')->willReturn('Test User');
        $user->method('getEmail')->willReturn('testuser@example.com');
        $user->method('getRegistration')->willReturn('123456');
        $user->method('getRole')->willReturn($role);

        $this->assertTrue($this->userRepository->createUser($user));

        $stmt = $this->connection->query("SELECT * FROM $this->table WHERE email = 'testuser@example.com'");

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('Test User', $result['name']);
        $this->assertEquals('testuser@example.com', $result['email']);
        $this->assertEquals('123456', $result['registration']);
        $this->assertEquals(1, $result['role']);
    }
    
    #[Covers('UserRepository::createUser')] 
    #[Covers('UserRepository::updateUser')] 
    #[Covers('Role::getId')] 
    #[Covers('User::getName')] 
    #[Covers('User::getEmail')] 
    #[Covers('User::getRegistration')] 
    #[Covers('User::getRole')]
    public function testUpdateUser(): void
    {
        $initialRole = $this->createMock(Role::class);
        $initialRole->method('getId')->willReturn(1);

        $initialUser = $this->createMock(User::class);
        $initialUser->method('getName')->willReturn('Initial User');
        $initialUser->method('getEmail')->willReturn('initialuser@example.com');
        $initialUser->method('getRegistration')->willReturn('123456');
        $initialUser->method('getRole')->willReturn($initialRole);

        $this->assertTrue($this->userRepository->createUser($initialUser));

        $stmt = $this->connection->query("SELECT id FROM $this->table WHERE email = 'initialuser@example.com'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $result["id"];

        $updatedRole = $this->createMock(Role::class);
        $updatedRole->method('getId')->willReturn(2);
        $updatedUser = $this->createMock(User::class);
        $updatedUser->method('getId')->willReturn($userId);
        $updatedUser->method('getName')->willReturn('Updated User');
        $updatedUser->method('getEmail')->willReturn('updateduser@example.com');
        $updatedUser->method('getRegistration')->willReturn('654321');
        $updatedUser->method('getRole')->willReturn($updatedRole);

        $this->assertTrue($this->userRepository->updateUser($updatedUser));

        $stmt = $this->connection->query("SELECT * FROM $this->table WHERE id = $userId");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('Updated User', $result['name']);
        $this->assertEquals('updateduser@example.com', $result['email']);
        $this->assertEquals('654321', $result['registration']);
        $this->assertEquals(2, $result['role']);
    }
    
    #[Covers('UserRepository::createUser')] 
    #[Covers('UserRepository::getUserById')] 
    #[Covers('Role::getId')] 
    #[Covers('User::getName')] 
    #[Covers('User::getEmail')] 
    #[Covers('User::getRegistration')] 
    #[Covers('User::getRole')]
    public function testGetUserById(): void
    {
        $initialRole = $this->createMock(Role::class);
        $initialRole->method('getId')->willReturn(1);

        $initialUser = $this->createMock(User::class);
        $initialUser->method('getName')->willReturn('Initial User');
        $initialUser->method('getEmail')->willReturn('initialuser@example.com');
        $initialUser->method('getRegistration')->willReturn('123456');
        $initialUser->method('getRole')->willReturn($initialRole);

        $this->assertTrue($this->userRepository->createUser($initialUser));

        $stmt = $this->connection->query("SELECT id FROM $this->table WHERE email = 'initialuser@example.com'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $result["id"];

        $user = $this->userRepository->getUserById($userId);
        $this->assertNotNull($user);
        $this->assertEquals('Initial User', $user->getName());
        $this->assertEquals('initialuser@example.com', $user->getEmail());
        $this->assertEquals('123456', $user->getRegistration());
        $this->assertEquals(1, $user->getRole()->getId());
    }

    #[Covers('UserRepository::createUser')] 
    #[Covers('UserRepository::getAllUsers')] 
    #[Covers('Role::getId')] 
    #[Covers('User::getName')] 
    #[Covers('User::getEmail')] 
    #[Covers('User::getRegistration')] 
    #[Covers('User::getRole')]
    public function testGetAllUsers(): void
    {
        $firstRole = $this->createMock(Role::class);
        $firstRole->method('getId')->willReturn(1);
        
        $firstUser = $this->createMock(User::class);
        $firstUser->method('getName')->willReturn('Professor User');
        $firstUser->method('getEmail')->willReturn('professor@example.com');
        $firstUser->method('getRegistration')->willReturn('123456');
        $firstUser->method('getRole')->willReturn($firstRole);
        
        $this->assertTrue($this->userRepository->createUser($firstUser));

        $secondRole = $this->createMock(Role::class);
        $secondRole->method('getId')->willReturn(2);
        
        $secondUser = $this->createMock(User::class);
        $secondUser->method('getName')->willReturn('Student User');
        $secondUser->method('getEmail')->willReturn('student@example.com');
        $secondUser->method('getRegistration')->willReturn('654321');
        $secondUser->method('getRole')->willReturn($secondRole);
        
        $this->assertTrue($this->userRepository->createUser($secondUser));

        $users = $this->userRepository->getAllUsers();

        $this->assertCount(4, $users);
        $this->assertEquals('Professor User', $users[0]['name']);
        $this->assertEquals('professor@example.com', $users[0]['email']);
        $this->assertEquals('123456', $users[0]['registration']);
        $this->assertEquals(1, $users[0]['role']);
        
        $this->assertEquals('Student User', $users[1]['name']);
        $this->assertEquals('student@example.com', $users[1]['email']);
        $this->assertEquals('654321', $users[1]['registration']);
        $this->assertEquals(2, $users[1]['role']);
    }
    
    #[Covers('UserRepository::createUser')] 
    #[Covers('UserRepository::deleteUser')] 
    #[Covers('Role::getId')] 
    #[Covers('User::getName')] 
    #[Covers('User::getEmail')] 
    #[Covers('User::getRegistration')] 
    #[Covers('User::getRole')]
    public function testDeleteUser()
    {
        $role = $this->createMock(Role::class);
        $role->method('getId')->willReturn(1);
        
        $user = $this->createMock(User::class);
        $user->method('getName')->willReturn('Professor User');
        $user->method('getEmail')->willReturn('professor@example.com');
        $user->method('getRegistration')->willReturn('123456');
        $user->method('getRole')->willReturn($role);

        $this->assertTrue($this->userRepository->createUser($user));

        $stmt = $this->connection->query("SELECT id FROM user WHERE email = 'professor@example.com'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $result['id'];

        $this->assertTrue($this->userRepository->deleteUser($userId));
        
        $stmt = $this->connection->query("SELECT * FROM user WHERE id = $userId");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertFalse($result);
    }
}
