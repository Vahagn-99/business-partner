<?php

namespace App\DTO\Validations\Product;

use App\Models\Category;
use App\Models\Product;
use App\Rules\FileExistsRule;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class SaveProductData extends Data
{
    public function __construct(
        public string   $name
        , public float  $price
        , public string $description
        , public bool   $in_stock
        , public int    $category_id
        , public array  $images
        , public ?int   $id
    )
    {
    }

    public static function rules(): array
    {
        return [
            'id' => ['nullable', Rule::exists(Product::query()->getModel()->getTable(), 'id')],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'description' => ['required', 'string', 'min:3', 'max:500'],
            'in_stock' => ['required', 'bool'],
            'category_id' => ['required', Rule::exists(Category::query()->getModel()->getTable(), 'id')],
            'images' => ['present', 'array'],
            'images.*' => ['required', 'string', new FileExistsRule()],
        ];
    }
}
