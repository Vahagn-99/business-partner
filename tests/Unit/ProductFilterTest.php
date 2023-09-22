<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\Filters\ProductFilter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductFilterTest extends TestCase
{
    use RefreshDatabase;

    public function testFilterByName()
    {
        // Create a Product model instance to use as a base query
        $query = Product::query();

        // Set up the filter input
        $filter = 'exampleFilter';

        // Create an instance of the ProductFilter
        $productFilter = new ProductFilter();

        // Call the filter method with the query builder and filter input
        $productFilter->filter($query, $filter);

        // Get the resulting SQL query
        $sql = $query->toSql();

        // Assert that the SQL query contains the "name LIKE ?" clause
        $this->assertStringContainsString("`name` LIKE ?", $sql);

        // Check if the bindings contain the filter value
        $bindings = $query->getBindings();
        $this->assertContains("%$filter%", $bindings);
    }
    public function testFilterCategoryName()
    {
        // Create a Product model instance to use as a base query
        $query = Product::query();

        // Set up the filter input
        $filter = 'exampleFilter';

        // Create an instance of the ProductFilter
        $productFilter = new ProductFilter();

        // Call the filter method with the query builder and filter input
        $productFilter->filter($query, $filter);

        // Get the resulting SQL query
        $sql = $query->toSql();

        // Assert that the SQL query contains the "name LIKE ?" clause
        $this->assertStringContainsString("`category_id` = `categories`.`id` and `name` = ?", $sql);

        // Check if the bindings contain the filter value
        $bindings = $query->getBindings();
        $this->assertContains("%$filter%", $bindings);
    }

}
