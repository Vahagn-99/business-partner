<?php

namespace App\Services\Files;

use App\DTO\Validations\Upload\StoreImageData;

trait CanSaveImages
{
    private function saveImages(StoreImageData $data): array
    {
        $urls = [];
        foreach ($data->images as $image) {
            $urls[] = $this->fileManager->apply($image, $data->owner)->url();
        }
        return $urls;
    }
}
