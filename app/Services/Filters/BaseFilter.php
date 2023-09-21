<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class BaseFilter
{
    protected Builder $query;

    public function apply(Builder $query, array $filters): Builder
    {
        foreach ($filters as $key => $filter) {
            $method = $this->handledName($key);
            if (method_exists($this, $method)) {
                $this->{$method}($query, $filter);
            }
        }
        return $this->query = $query;
    }

    private function handledName(string $name): string
    {
        return Str::camel($name);
    }
}
