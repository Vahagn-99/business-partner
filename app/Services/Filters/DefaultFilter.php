<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;

interface DefaultFilter
{
    public function filter(Builder $query, string $filter): void;
}
