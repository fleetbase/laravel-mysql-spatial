<?php

use Fleetbase\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GeometryModel.
 *
 * @property int                                          id
 * @property Fleetbase\LaravelMysqlSpatial\Types\Point      location
 * @property Fleetbase\LaravelMysqlSpatial\Types\LineString line
 * @property Fleetbase\LaravelMysqlSpatial\Types\LineString shape
 */
class GeometryModel extends Model
{
    use SpatialTrait;

    protected $table = 'geometry';

    protected $spatialFields = ['location', 'line', 'multi_geometries'];
}
