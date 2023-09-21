<?php

namespace Tests\Unit;

use App\Services\Files\FileManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileManagerTest extends TestCase
{
    public function test_it_can_apply_file()
    {
        $fileName = 'test_image.jpg';
        $fakeFile = UploadedFile::fake()->image($fileName);
        $fileManager = new FileManager();

        $directory = 'uploads';
        $prefix = 'prefix_';
        $access = 'public';
        $actualPath = $directory . '/' . $fileName;
        $fileManager->apply($fakeFile, $directory, true, $prefix, $access);
        $this->assertEquals($actualPath, $fileManager->path());
        $this->assertEquals($fakeFile->getClientOriginalName(), $fileManager->name());
        $this->assertTrue(Storage::disk($access)->exists($fileManager->path()));
    }

    public function test_it_can_generate_url()
    {
        $name = 'test_image_.jpg';

        $fakeFile = UploadedFile::fake()->image($name);
        $fileManager = new FileManager();

        $directory = '/uploads';
        $prefix = 'prefix_';
        $access = 'public';
        $fileManager->apply($fakeFile, $directory, true, $prefix, $access);

        $url = $fileManager->url();
        $expectedUrl = url($directory . '/' . $name);
        $this->assertEquals($expectedUrl, $url);
    }
}

