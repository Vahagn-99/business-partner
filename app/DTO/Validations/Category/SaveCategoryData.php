<?php

namespace App\DTO\Validations\Category;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class SaveCategoryData extends Data
{
    public function __construct(
          public string $name
        , public ?int $parent_id = null
        , public ?int $id = null
    )
    {
    }

    public static function rules(): array
    {
        $table = Category::query()->getModel()->getTable();
        return [
            'id' => ['nullable', Rule::exists($table, 'id')],
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', Rule::exists($table, 'id')],
        ];
    }
}
