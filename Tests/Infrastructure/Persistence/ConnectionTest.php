<?php
namespace Tests\Infrastructure\Persistence;

use PDO;
use PHPUnit\Framework\TestCase;
use App\Library\Infrastructure\Persistence\Connection;

class ConnectionTest extends TestCase
{
    public function testSingletonInstance()
    {
        $instance1 = Connection::getInstance();
        $instance2 = Connection::getInstance();
        $this->assertInstanceOf(PDO::class, $instance1);
        $this->assertSame($instance1, $instance2, "As instâncias não são as mesmas");
    }
    
    public function testConnectionIsPersistent()
    {
        $instance = Connection::getInstance();
        $attributes = $instance->getAttribute(PDO::ATTR_PERSISTENT);
        $this->assertTrue($attributes, "A conexão não é persistente");
    }
}
