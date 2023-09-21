<?php

namespace App\Services\Files;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasImages
{
    public function images(): MorphMany
    {
        return $this->morphMany(
            Image::class
            , 'imageOwner'
            , 'image_owner_type'
            , 'image_owner_id'
            , 'id'
        );
    }

    public function addImage(string $url): Model
    {
        return $this->images()->make([
            'url' => $url
        ]);
    }

    public function addImages(array $urls): array
    {
        $models = [];
        foreach ($urls as $url) {
            $models [] = $this->addImage($url);
        }
        return $models;
    }
}
