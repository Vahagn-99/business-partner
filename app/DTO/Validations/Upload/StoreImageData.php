<?php

namespace App\DTO\Validations\Upload;

use App\Enums\ImageOwnerType;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\Enum;
use Spatie\LaravelData\Data;

class StoreImageData extends Data
{
    /** @var array<UploadedFile> $images */
    public function __construct(
        public array $images,
        public string $owner,
    )
    {
    }

    public static function rules(): array
    {
        return [
              'owner' => ['required', new Enum(ImageOwnerType::class)]
            , 'images' => ['present', 'array', 'min:1', 'max:5']
            , 'images.*' => ['required', 'mimes:png,jpg']
        ];
    }
}
