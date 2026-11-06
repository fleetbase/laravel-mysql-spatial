<?php

use Fleetbase\LaravelMysqlSpatial\MysqlConnection;
use Fleetbase\LaravelMysqlSpatial\Schema\Builder;
use PHPUnit\Framework\TestCase;
use Stubs\PDOStub;

class MysqlConnectionTest extends TestCase
{
    private $mysqlConnection;

    private $mysqlConfig = ['driver' => 'mysql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo'];

    protected function setUp(): void
    {
        $this->mysqlConnection = new MysqlConnection(new PDOStub(), 'database', 'prefix', $this->mysqlConfig);
    }

    public function testGetSchemaBuilder()
    {
        $builder = $this->mysqlConnection->getSchemaBuilder();

        $this->assertInstanceOf(Builder::class, $builder);
    }

    public function testConstructorDoesNotResolveThePdoConnection()
    {
        $resolved = 0;
        $resolver = function () use (&$resolved) {
            $resolved++;

            return new PDOStub();
        };

        $connection = new MysqlConnection($resolver, 'database', 'prefix', $this->mysqlConfig);

        $this->assertSame(0, $resolved, 'Building the connection must not connect to the database.');

        $connection->getPdo();

        $this->assertSame(1, $resolved);
    }
}
