<?php

use Fleetbase\LaravelMysqlSpatial\MysqlConnection;
use Fleetbase\LaravelMysqlSpatial\Schema\Builder;
use PHPUnit\Framework\TestCase;
use Stubs\PDOStub;

class MysqlConnectionTest extends TestCase
{
    private $mysqlConnection;

    /**
     * `server_version` lets Doctrine pick the platform without querying the (stub) PDO handle.
     */
    private $mysqlConfig = ['driver' => 'mysql', 'prefix' => 'prefix', 'database' => 'database', 'name' => 'foo', 'server_version' => '8.0.30'];

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

    public function testGeometryTypeMappingsAreRegisteredOnFirstDoctrineUse()
    {
        $resolved = 0;
        $resolver = function () use (&$resolved) {
            $resolved++;

            return new PDOStub();
        };

        $connection = new MysqlConnection($resolver, 'database', 'prefix', $this->mysqlConfig);
        $platform   = $connection->getDoctrineSchemaManager()->getDatabasePlatform();

        $this->assertSame(1, $resolved);

        foreach (['geometry', 'point', 'linestring', 'polygon', 'multipoint', 'multilinestring', 'multipolygon', 'geometrycollection', 'geomcollection'] as $type) {
            $this->assertTrue($platform->hasDoctrineTypeMappingFor($type), "Expected a Doctrine type mapping for {$type}.");
            $this->assertSame('string', $platform->getDoctrineTypeMapping($type));
        }

        // repeated access reuses the same Doctrine connection and does not re-register
        $this->assertSame($connection->getDoctrineConnection(), $connection->getDoctrineConnection());
        $this->assertSame(1, $resolved);
    }
}
