<?php

namespace App\DTO\Validations\Product;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class AddProductToCartData extends Data
{
    public function __construct(
          public int   $id
        , public int $quantity
    )
    {
    }

    public static function rules(): array
    {
        return [
            'id' => ['required', Rule::exists(Product::query()->getModel()->getTable(), 'id')],
            'quantity' => ['required', 'numeric'],
        ];
    }
}
