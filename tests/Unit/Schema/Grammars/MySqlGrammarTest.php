<?php

use Fleetbase\LaravelMysqlSpatial\MysqlConnection;
use Fleetbase\LaravelMysqlSpatial\Schema\Blueprint;
use Stubs\PDOStub;

class MySqlGrammarTest extends BaseTestCase
{
    public function testAddingGeometry()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->geometry('foo');
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` GEOMETRY not null', $statements[0]);
    }

    public function testAddingPoint()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->point('foo');
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` POINT not null', $statements[0]);
    }

    public function testAddingLinestring()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->linestring('foo');
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` LINESTRING not null', $statements[0]);
    }

    public function testAddingPolygon()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->polygon('foo');
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` POLYGON not null', $statements[0]);
    }

    public function testAddingMultipoint()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->multipoint('foo');
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` MULTIPOINT not null', $statements[0]);
    }

    public function testAddingMultiLinestring()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->multilinestring('foo');
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` MULTILINESTRING not null', $statements[0]);
    }

    public function testAddingMultiPolygon()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->multipolygon('foo');
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` MULTIPOLYGON not null', $statements[0]);
    }

    public function testAddingGeometryCollection()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->geometrycollection('foo');
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` GEOMETRYCOLLECTION not null', $statements[0]);
    }

    public function testAddingGeometryWithSrid()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->geometry('foo', 4326);
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` GEOMETRY not null srid 4326', $statements[0]);
    }

    public function testAddingPointWithSrid()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->point('foo', 4326);
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` POINT not null srid 4326', $statements[0]);
    }

    public function testAddingLinestringWithSrid()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->linestring('foo', 4326);
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` LINESTRING not null srid 4326', $statements[0]);
    }

    public function testAddingPolygonWithSrid()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->polygon('foo', 4326);
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` POLYGON not null srid 4326', $statements[0]);
    }

    public function testAddingMultipointWithSrid()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->multipoint('foo', 4326);
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` MULTIPOINT not null srid 4326', $statements[0]);
    }

    public function testAddingMultiLinestringWithSrid()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->multilinestring('foo', 4326);
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` MULTILINESTRING not null srid 4326', $statements[0]);
    }

    public function testAddingMultiPolygonWithSrid()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->multipolygon('foo', 4326);
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` MULTIPOLYGON not null srid 4326', $statements[0]);
    }

    public function testAddingGeometryCollectionWithSrid()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->geometrycollection('foo', 4326);
        $statements = $blueprint->toSql();

        $this->assertEquals(1, count($statements));
        $this->assertEquals('alter table `test` add `foo` GEOMETRYCOLLECTION not null srid 4326', $statements[0]);
    }

    public function testAddRemoveSpatialIndex()
    {
        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->point('foo');
        $blueprint->spatialIndex('foo');
        $addStatements = $blueprint->toSql();

        $this->assertEquals(2, count($addStatements));
        $this->assertEquals('alter table `test` add spatial `test_foo_spatial`(`foo`)', $addStatements[1]);

        $blueprint = new Blueprint($this->getConnection(), 'test');
        $blueprint->dropSpatialIndex(['foo']);
        $blueprint->dropSpatialIndex('test_foo_spatial');
        $dropStatements = $blueprint->toSql();

        $expectedSql = 'alter table `test` drop index `test_foo_spatial`';
        $this->assertEquals(2, count($dropStatements));
        $this->assertEquals($expectedSql, $dropStatements[0]);
        $this->assertEquals($expectedSql, $dropStatements[1]);
    }

    /**
     * @return Connection
     */
    protected function getConnection()
    {
        $connection = new MysqlConnection(new PDOStub(), 'database', '', ['database' => 'database', 'name' => 'foo']);
        $connection->useDefaultSchemaGrammar();

        return $connection;
    }
}
