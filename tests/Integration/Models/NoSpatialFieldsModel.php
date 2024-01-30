<?php

use Fleetbase\Database\Spatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NoSpatialFieldsModel.
 *
 * @property \Fleetbase\Database\Spatial\Types\Geometry geometry
 */
class NoSpatialFieldsModel extends Model
{
    use SpatialTrait;

    protected $table = 'no_spatial_fields';

    public $timestamps = false;
}
