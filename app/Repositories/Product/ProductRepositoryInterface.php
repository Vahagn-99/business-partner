<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductRepositoryInterface
{
    public function getAllProducts(array $filter): Collection;

    public function saveProduct(array $data): Product|Model;

    public function loadRelations(Product $product): Product|Model;

    public function deleteProduct(Product $product): bool;
}
