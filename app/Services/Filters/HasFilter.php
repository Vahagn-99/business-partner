<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use RuntimeException;

/**
 * @method static Builder filter(array $filters, ?BaseFilter $filter = null)
 */
trait HasFilter
{
    /**
     * This filter can be used for searching any data from the database.
     *
     * @param Builder $query
     * @param array $filters
     * @param BaseFilter|null $filter
     * @return void
     * @throws RuntimeException
     */
    protected function scopeFilter(Builder $query, array $filters, ?BaseFilter $filter = null): void
    {
        $filter = $filter ?? $this->getImplementer();
        if (!$filter) {
            throw new RuntimeException("Can't make filter without filter implementer");
        }
        $filter->apply($query, $filters);
    }

    /**
     * Get filter implementer for the current Model.
     * If $filterImplementer is not defined,
     * try to get by model name and "Filter" prefix.
     * If none of these help return null.
     *
     * @return BaseFilter|null
     */
    private function getImplementer(): ?BaseFilter
    {
        if (property_exists($this, 'filterImplementer')) {
            return App::make($this->filterImplementer);
        }
        $path = __DIR__ . '/' . class_basename($this) . 'Filter.php';

        if (File::exists($path)) {
            return App::make(__NAMESPACE__ . '\\' . class_basename($this) . 'Filter');
        }
        return null;
    }
}
