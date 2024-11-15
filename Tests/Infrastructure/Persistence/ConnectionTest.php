<?php
namespace Tests\Infrastructure\Persistence;

use App\Library\Infrastructure\Persistence\Connection;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\Covers;

#[CoversClass(Connection::class)]
class ConnectionTest extends TestCase
{

    #[Covers('getInstance')]
    #[Covers('__construct')]
    public function testSingletonInstance()
    {
        $instance1 = Connection::getInstance();
        $instance2 = Connection::getInstance();
        $this->assertInstanceOf(PDO::class, $instance1);
        $this->assertSame($instance1, $instance2, "As instâncias não são as mesmas");
    }

    #[Covers('getInstance')]
    #[Covers('__construct')] 
    #[Covers('resetInstance')]
    public function testConnectionIsPersistent()
    {
        Connection::resetInstance();
        $instance = Connection::getInstance();
        $attributes = $instance->getAttribute(PDO::ATTR_PERSISTENT);
        $this->assertTrue($attributes);
    }
}
