<?php

namespace Schema;

use Fleetbase\LaravelMysqlSpatial\MysqlConnection;
use Fleetbase\LaravelMysqlSpatial\Schema\Blueprint;
use Fleetbase\LaravelMysqlSpatial\Schema\Builder;

class BuilderTest extends \BaseTestCase
{
    public function testReturnsCorrectBlueprint()
    {
        $connection = new MysqlConnection(new \Stubs\PDOStub(), 'database', '', ['database' => 'database', 'name' => 'foo']);
        $connection->useDefaultSchemaGrammar();

        $mock = \Mockery::mock(Builder::class, [$connection]);
        $mock->makePartial()->shouldAllowMockingProtectedMethods();
        $blueprint = $mock->createBlueprint('test', function () {
        });

        $this->assertInstanceOf(Blueprint::class, $blueprint);
    }
}
