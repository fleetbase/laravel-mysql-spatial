<?php

use Fleetbase\LaravelMysqlSpatial\MysqlConnection;
use Fleetbase\LaravelMysqlSpatial\Schema\Blueprint;
use Fleetbase\LaravelMysqlSpatial\Schema\Builder;
use Fleetbase\LaravelMysqlSpatial\Types\Point;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Runs what Laravel 11+ changed under this package on real MySQL: the Blueprint constructor,
 * the geometry() and spatialIndex() signatures, the grammar constructor, and removed Doctrine.
 */
class SchemaCompatibilityTest extends IntegrationBaseTestCase
{
    public function tearDown(): void
    {
        Schema::dropIfExists('compat_places');

        parent::tearDown();
    }

    public function testTheConnectionAndSchemaBuilderAreThePackagesOwn()
    {
        $this->assertInstanceOf(MysqlConnection::class, DB::connection());
        $this->assertInstanceOf(Builder::class, Schema::getFacadeRoot());
    }

    public function testBuildingAConnectionDoesNotConnect()
    {
        $resolved   = 0;
        $connection = new MysqlConnection(function () use (&$resolved) {
            $resolved++;
        }, 'database', '', ['database' => 'database', 'name' => 'lazy']);

        $connection->getSchemaBuilder();

        $this->assertSame(0, $resolved);
    }

    public function testGeometrySpatialIndexAndDropIndexRoundTrip()
    {
        Schema::create('compat_places', function (Blueprint $table) {
            $table->increments('id');
            $table->geometry('legacy_srid', 3857)->nullable();
            $table->geometry('framework_srid', null, 4326)->nullable();
            $table->point('location', 3857);
            $table->spatialIndex('location');
        });

        $columns = collect(DB::select("select column_name as name, srs_id as srid from information_schema.columns where table_schema = database() and table_name = 'compat_places'"))
            ->pluck('srid', 'name');
        $this->assertEquals(3857, $columns['legacy_srid']);
        $this->assertEquals(4326, $columns['framework_srid']);
        $this->assertEquals(3857, $columns['location']);

        $this->assertTrue(Schema::hasIndex('compat_places', 'compat_places_location_spatial'));

        Schema::table('compat_places', function (Blueprint $table) {
            $table->dropSpatialIndex(['location']);
        });

        $this->assertFalse(Schema::hasIndex('compat_places', 'compat_places_location_spatial'));
    }

    public function testChangingATableWithSpatialColumnsNeedsNoDoctrine()
    {
        $this->assertFalse(class_exists(Doctrine\DBAL\Types\Type::class));

        Schema::create('compat_places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 10)->nullable();
            $table->point('location')->nullable();
        });

        Schema::table('compat_places', function (Blueprint $table) {
            $table->string('name', 40)->nullable()->change();
        });

        $this->assertEquals('point', Schema::getColumnType('compat_places', 'location'));
        $this->assertEquals('varchar(40)', Schema::getColumnType('compat_places', 'name', true));
    }

    public function testASpatialValueReadsBackThroughTheConnection()
    {
        Schema::create('compat_places', function (Blueprint $table) {
            $table->increments('id');
            $table->point('location', 4326);
        });

        DB::table('compat_places')->insert([
            'location' => DB::raw("ST_GeomFromText('POINT(103.8 1.35)', 4326, 'axis-order=long-lat')"),
        ]);

        $wkb   = DB::table('compat_places')->value('location');
        $point = Fleetbase\LaravelMysqlSpatial\Types\Geometry::fromWKB($wkb);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertEqualsWithDelta(1.35, $point->getLat(), 0.0001);
        $this->assertEqualsWithDelta(103.8, $point->getLng(), 0.0001);
    }
}
