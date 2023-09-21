<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts(): Collection
    {
        return Product::with(['images', 'category'])->get();
    }

    public function saveProduct(array $data): Product|Model
    {
        /** @var Product $product */
        $product = Product::query()->updateOrCreate(['id' => $data['id']], $data);
        $product->addImages($data['images']);
        return $product;
    }

    public function deleteProduct(Product $product): bool
    {
        $product->images()->delete();
        return $product->delete();
    }

    public function loadRelations(Product $product): Product|Model
    {
        return $product->load(['images', 'category']);
    }
}
