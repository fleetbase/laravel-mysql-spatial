<?php

use Fleetbase\Database\Spatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GeometryModel.
 *
 * @property int                                          id
 * @property \Fleetbase\Database\Spatial\Types\Point      location
 * @property \Fleetbase\Database\Spatial\Types\LineString line
 * @property \Fleetbase\Database\Spatial\Types\LineString shape
 */
class GeometryModel extends Model
{
    use SpatialTrait;

    protected $table = 'geometry';

    protected $spatialFields = ['location', 'line', 'multi_geometries'];
}
