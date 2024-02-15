<?php

namespace Fleetbase\LaravelMysqlSpatial\Schema;

use Closure;
use Illuminate\Database\Schema\MySqlBuilder;

class Builder extends MySqlBuilder
{
    /**
     * Create a new command set with a Closure.
     *
     * @param string $table
     *
     * @return Blueprint
     */
    protected function createBlueprint($table, ?\Closure $callback = null)
    {
        return new Blueprint($table, $callback);
    }
}
