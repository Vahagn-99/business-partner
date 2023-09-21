<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Category $resource
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getKey()
            , 'name' => $this->resource->name
            , 'parent_id' => $this->resource->parent_id
            , 'level' => $this->resource->level
            , 'parent' => self::make($this->whenLoaded('parent'))
            , 'subcategories' => self::collection($this->whenLoaded('subcategories'))
        ];
    }
}
