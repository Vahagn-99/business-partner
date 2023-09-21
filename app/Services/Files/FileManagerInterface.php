<?php

namespace App\Services\Files;

use Illuminate\Http\UploadedFile;

interface FileManagerInterface
{
    public const __DEFAULT_PATH__ = '/';

    /**
     * @param UploadedFile $file
     * @param $directory
     * @param bool $usOriginalName
     * @param string $prefix
     * @param string $access
     * @return $this
     */
    public function apply(
        UploadedFile $file
        ,            $directory
        , bool       $usOriginalName = false
        , string     $prefix = ''
        , string     $access = 'public'
    ): static;

    /**
     * @return string
     */
    public function path(): string;

    /**
     * @param array $parameters
     * @param bool $secure
     * @return string
     */
    public function url(array $parameters = [], bool $secure = true): string;

    /**
     * @return string
     */
    public function name(): string;
}
