<?php

namespace App\Http\Controllers\Api;

use App\DTO\Validations\Upload\StoreImageData;
use App\Http\Controllers\Controller;
use App\Services\Files\CanSaveImages;
use App\Services\Files\FileManagerInterface;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Image",
 *     description="Operations related with uploading images"
 * )
 */
class ImageUploadController extends Controller
{
    use CanSaveImages;

    private FileManagerInterface $fileManager;

    public function __construct(FileManagerInterface $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/upload-image",
     *     summary="Upload image",
     *     tags={"Image"},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="succes"),
     *     @OA\Response(response="422", description="validation error"),
     * )
     */
    public function __invoke(StoreImageData $data): JsonResponse
    {
        $urls = $this->saveImages($data);
        return response()->json($urls);
    }
}
