<?php

use Fleetbase\Database\Spatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WithSridModel.
 *
 * @property int                                          id
 * @property \Fleetbase\Database\Spatial\Types\Point      location
 * @property \Fleetbase\Database\Spatial\Types\LineString line
 * @property \Fleetbase\Database\Spatial\Types\LineString shape
 */
class WithSridModel extends Model
{
    use SpatialTrait;

    protected $table = 'with_srid';

    protected $spatialFields = ['location', 'line'];

    public $timestamps = false;
}
