<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;

final class ProductFilter extends BaseFilter implements DefaultFilter
{
    public function filter(Builder $query, string $filter): void
    {
        $query->where(function (Builder $query) use ($filter) {
            $query->where('name', 'LIKE', "%$filter%");
            $query->orWhereRelation('category', fn(Builder $query) => $query->where('name', $filter));
        });
    }
}
