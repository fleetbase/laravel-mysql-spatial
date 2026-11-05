<?php

namespace Fleetbase\LaravelMysqlSpatial\Schema;

use Illuminate\Database\Schema\Blueprint as IlluminateBlueprint;

class Blueprint extends IlluminateBlueprint
{
    /**
     * Add a geometry column on the table.
     *
     * The framework's signature is ($column, $subtype, $srid), but this package's own is
     * ($column, $srid). An integer second argument is still read as the SRID so existing
     * migrations keep their meaning.
     *
     * @param string          $column
     * @param string|int|null $subtype
     * @param int|null        $srid
     *
     * @return \Illuminate\Support\Fluent
     */
    public function geometry($column, $subtype = null, $srid = null)
    {
        if (is_int($subtype)) {
            $srid = $subtype;
        }

        return $this->addColumn('geometry', $column, compact('srid'));
    }

    /**
     * Add a point column on the table.
     *
     * @param string   $column
     * @param int|null $srid
     *
     * @return \Illuminate\Support\Fluent
     */
    public function point($column, $srid = null)
    {
        return $this->addColumn('point', $column, compact('srid'));
    }

    /**
     * Add a linestring column on the table.
     *
     * @param string   $column
     * @param int|null $srid
     *
     * @return \Illuminate\Support\Fluent
     */
    public function lineString($column, $srid = null)
    {
        return $this->addColumn('linestring', $column, compact('srid'));
    }

    /**
     * Add a polygon column on the table.
     *
     * @param string   $column
     * @param int|null $srid
     *
     * @return \Illuminate\Support\Fluent
     */
    public function polygon($column, $srid = null)
    {
        return $this->addColumn('polygon', $column, compact('srid'));
    }

    /**
     * Add a multipoint column on the table.
     *
     * @param string   $column
     * @param int|null $srid
     *
     * @return \Illuminate\Support\Fluent
     */
    public function multiPoint($column, $srid = null)
    {
        return $this->addColumn('multipoint', $column, compact('srid'));
    }

    /**
     * Add a multilinestring column on the table.
     *
     * @param string   $column
     * @param int|null $srid
     *
     * @return \Illuminate\Support\Fluent
     */
    public function multiLineString($column, $srid = null)
    {
        return $this->addColumn('multilinestring', $column, compact('srid'));
    }

    /**
     * Add a multipolygon column on the table.
     *
     * @param string   $column
     * @param int|null $srid
     *
     * @return \Illuminate\Support\Fluent
     */
    public function multiPolygon($column, $srid = null)
    {
        return $this->addColumn('multipolygon', $column, compact('srid'));
    }

    /**
     * Add a geometrycollection column on the table.
     *
     * @param string   $column
     * @param int|null $srid
     *
     * @return \Illuminate\Support\Fluent
     */
    public function geometryCollection($column, $srid = null)
    {
        return $this->addColumn('geometrycollection', $column, compact('srid'));
    }

    /**
     * Specify a spatial index for the table.
     *
     * @param string|array $columns
     * @param string       $name
     * @param string|null  $operatorClass unused on MySQL
     *
     * @return \Illuminate\Support\Fluent
     */
    public function spatialIndex($columns, $name = null, $operatorClass = null)
    {
        return $this->indexCommand('spatial', $columns, $name);
    }

    /**
     * Indicate that the given index should be dropped.
     *
     * @param string|array $index
     *
     * @return \Illuminate\Support\Fluent
     */
    public function dropSpatialIndex($index)
    {
        return $this->dropIndexCommand('dropIndex', 'spatial', $index);
    }
}
