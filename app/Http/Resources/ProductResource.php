<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Product $resource
 */
class ProductResource extends JsonResource
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
            , 'description' => $this->resource->description
            , 'in_stock' => $this->resource->in_stock
            , 'category_id' => $this->resource->category_id
            , 'images' => ImageResource::collection($this->whenLoaded('images'))
            , 'category' => CategoryResource::make($this->whenLoaded('category'))
        ];
    }
}
