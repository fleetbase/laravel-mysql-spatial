<?php

namespace Fleetbase\LaravelMysqlSpatial;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fleetbase\LaravelMysqlSpatial\Schema\Builder;
use Fleetbase\LaravelMysqlSpatial\Schema\Grammars\MySqlGrammar;
use Illuminate\Database\MySqlConnection as IlluminateMySqlConnection;

class MysqlConnection extends IlluminateMySqlConnection
{
    /**
     * MySQL geometry column types mapped to Doctrine's string type so schema changes on
     * tables with spatial columns do not throw "unknown database type" errors.
     *
     * @var array<int, string>
     */
    protected const GEOMETRY_TYPES = [
        'geometry',
        'point',
        'linestring',
        'polygon',
        'multipoint',
        'multilinestring',
        'multipolygon',
        'geometrycollection',
        'geomcollection',
    ];

    /**
     * Whether the geometry type mappings have been registered on the Doctrine platform.
     */
    protected bool $geometryTypeMappingsRegistered = false;

    /**
     * Get the Doctrine DBAL connection, registering the geometry type mappings on first use.
     *
     * The mappings used to be registered in the constructor through getDoctrineSchemaManager(),
     * which resolves the PDO handle and therefore connected to MySQL the moment the connection
     * object was built. Anything that merely resolved DB::connection() - DB::listen(), a schema
     * check in a service provider, `artisan package:discover` during `composer install` - opened
     * a database connection, and failed outright when no database was reachable. Doctrine is only
     * needed for schema introspection, so the mappings are registered lazily when it is first used.
     *
     * @return \Doctrine\DBAL\Connection
     */
    public function getDoctrineConnection()
    {
        $connection = parent::getDoctrineConnection();

        if (!$this->geometryTypeMappingsRegistered) {
            $this->geometryTypeMappingsRegistered = true;
            $this->registerGeometryTypeMappings($connection->getDatabasePlatform());
        }

        return $connection;
    }

    /**
     * Prevent geometry type fields from throwing a 'type not found' error when changing them.
     */
    protected function registerGeometryTypeMappings(AbstractPlatform $platform): void
    {
        foreach (static::GEOMETRY_TYPES as $type) {
            $platform->registerDoctrineTypeMapping($type, 'string');
        }
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new MySqlGrammar());
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \Illuminate\Database\Schema\MySqlBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new Builder($this);
    }
}
