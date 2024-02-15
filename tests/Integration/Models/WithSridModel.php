<?php

use Fleetbase\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WithSridModel.
 *
 * @property int                                          id
 * @property Fleetbase\LaravelMysqlSpatial\Types\Point      location
 * @property Fleetbase\LaravelMysqlSpatial\Types\LineString line
 * @property Fleetbase\LaravelMysqlSpatial\Types\LineString shape
 */
class WithSridModel extends Model
{
    use SpatialTrait;

    protected $table = 'with_srid';

    protected $spatialFields = ['location', 'line'];

    public $timestamps = false;
}
