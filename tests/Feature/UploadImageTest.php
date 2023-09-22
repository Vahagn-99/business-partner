<?php

namespace Tests\Feature;

use App\DTO\Validations\Upload\StoreImageData;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UploadImageTest extends TestCase
{
    public function test_user_can_upload_image()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        Sanctum::actingAs($user);

        $imageFile = UploadedFile::fake()->image('test_image.jpg'); // Создаем фейковый файл изображения

        // Создайте экземпляр StoreImageData с данными
        $images = [$imageFile];
        $imageData = new StoreImageData(
            $images
            , 'products');

        $response = $this->json('post', '/api/v1/upload-image', $imageData->toArray());
        $response->assertStatus(200);

        $imageUrls = $response->json();
        $this->assertNotEmpty($imageUrls);

        foreach ($imageUrls as $imageUrl) {
            $path = Str::replace(env('APP_URL'), '', $imageUrl);
            $this->assertTrue(Storage::disk('public')->exists($path));
        }

        foreach ($imageUrls as $imageUrl) {
            Storage::disk('public')->delete($imageUrl);
        }
    }
}
