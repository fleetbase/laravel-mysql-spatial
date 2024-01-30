<?php

namespace Fleetbase\Database\Spatial\Eloquent;

use Illuminate\Database\Query\Expression;

class SpatialExpression extends Expression
{
    public function getValue()
    {
        return "ST_GeomFromText(?, ?, 'axis-order=long-lat')";
    }

    public function getSpatialValue()
    {
        return $this->value->toWkt();
    }

    public function getSrid()
    {
        return $this->value->getSrid();
    }
}
