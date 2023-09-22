<?php

namespace App\Services\Files;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileManager implements FileManagerInterface
{
    /**
     * @var UploadedFile
     */
    private UploadedFile $file;
    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $path;

    /**
     * @param UploadedFile $file
     * @param $directory
     * @param bool $usOriginalName
     * @param string $prefix
     * @param string $access
     * @return FileManagerInterface
     */
    public function apply(
        UploadedFile $file
        ,            $directory
        , bool       $usOriginalName = false
        , string     $prefix = ''
        , string     $access = 'public'
    ): static
    {
        $this->setFile($file);
        $this->name = $this->designate($usOriginalName, $prefix);
        $this->path = $this->upload($directory, $access);
        return $this;
    }

    /**
     * @param string $prefix
     * @return string
     */
    private function generateName(string $prefix): string
    {
        return Str::random(25) . "$prefix." . $this->file->getClientOriginalExtension();
    }

    /**
     * @return string
     */
    private function originalName(): string
    {
        return $this->file->getClientOriginalName();
    }

    /**
     * @param bool $usOriginalName
     * @param string $prefix
     * @return string
     */
    private function designate(bool $usOriginalName, string $prefix = ''): string
    {
        return $usOriginalName ? $this->originalName() : $this->generateName($prefix);
    }

    /**
     * @param UploadedFile $file
     * @return void
     */
    private function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }

    /**
     * @param $directory
     * @param $access
     * @return string|false
     */
    private function upload($directory, $access): string|false
    {
        return $this->file->storeAs(self::__DEFAULT_PATH__ . $directory, $this->name, $access);
    }

    /**
     * @return UploadedFile
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * @param array $parameters
     * @param bool $secure
     * @return string
     */
    public function url(array $parameters = [], bool $secure = false): string
    {
        $secure = app()->isProduction();
        return url($this->path, $parameters, $secure);
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}
