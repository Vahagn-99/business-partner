<?php

namespace App\DTO\Validations\Product;

use Spatie\LaravelData\Data;

class FilterProductData extends Data
{
    public function __construct(
        public string|int|bool|null $filter
    )
    {
    }

    public static function rules(): array
    {
        return [
            'filter' => ['string', 'sometimes', 'max:50']
        ];
    }
}
