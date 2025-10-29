<?php

declare(strict_types=1);

namespace App\MoonShine\Fields;

use MoonShine\AssetManager\Css;
use MoonShine\AssetManager\Js;
use MoonShine\UI\Fields\Textarea;

class Trix extends Textarea
{
    protected string $view = 'porcelanosa-trix::trix';

    protected bool $useLfm = false;
    protected string $lfmType = 'image';
    protected string $lfmPrefix = '/laravel-filemanager';
    protected string $lfmPath = 'uploads';
    protected array $allowedFileTypes = [];
    protected ?int $maxUploadSize = null;
    protected ?int $maxImageDimension = null;
    protected ?int $minImageDimension = null;

    protected function assets(): array
    {
        return [
          Js::make('vendor/porcelanosa-trix/trix.umd.min.js'),
          Css::make('vendor/porcelanosa-trix/trix.css'),
        ];
    }

    /**
     * Enable Laravel File Manager integration
     */
    public function useLaravelFileManager(bool $use = true): self
    {
        $this->useLfm = $use;
        return $this;
    }

    /**
     * Set LFM type (image or file)
     */
    public function lfmType(string $type): self
    {
        $this->lfmType = in_array($type, ['image', 'file']) ? $type : 'image';
        return $this;
    }

    /**
     * Set LFM route prefix
     */
    public function lfmPrefix(string $prefix): self
    {
        $this->lfmPrefix = $prefix;
        return $this;
    }

    /**
     * Set storage path for uploads
     */
    public function lfmPath(string $path): self
    {
        $this->lfmPath = trim($path, '/');
        return $this;
    }

    /**
     * Set allowed file types (MIME types)
     * Example: ['image/jpeg', 'image/png', 'application/pdf']
     */
    public function allowedFileTypes(array $types): self
    {
        $this->allowedFileTypes = $types;
        return $this;
    }

    /**
     * Set maximum upload size in kilobytes
     */
    public function maxUploadSize(int $sizeKb): self
    {
        $this->maxUploadSize = $sizeKb;
        return $this;
    }

    /**
     * Set maximum image dimension (width or height) in pixels
     */
    public function maxImageDimension(int $pixels): self
    {
        $this->maxImageDimension = $pixels;
        return $this;
    }

    /**
     * Set minimum image dimension (width or height) in pixels
     */
    public function minImageDimension(int $pixels): self
    {
        $this->minImageDimension = $pixels;
        return $this;
    }

    public function isLfmEnabled(): bool
    {
        return $this->useLfm;
    }

    public function getLfmType(): string
    {
        return $this->lfmType;
    }

    public function getLfmPrefix(): string
    {
        return $this->lfmPrefix;
    }

    public function getLfmPath(): string
    {
        return $this->lfmPath;
    }

    public function getAllowedFileTypes(): array
    {
        // Default allowed types for images if not specified
        if (empty($this->allowedFileTypes) && $this->lfmType === 'image') {
            return ['image/jpeg', 'image/png', 'image/jpg'];
        }

        return $this->allowedFileTypes;
    }

    public function getMaxUploadSize(): ?int
    {
        return $this->maxUploadSize;
    }

    public function getMaxImageDimension(): ?int
    {
        return $this->maxImageDimension;
    }

    public function getMinImageDimension(): ?int
    {
        return $this->minImageDimension;
    }

    protected function viewData(): array
    {
        return [
          'useLfm' => $this->isLfmEnabled(),
          'lfmConfig' => [
            'type' => $this->getLfmType(),
            'prefix' => $this->getLfmPrefix(),
            'path' => $this->getLfmPath(),
            'allowedFileTypes' => $this->getAllowedFileTypes(),
            'maxUploadSize' => $this->getMaxUploadSize(),
            'maxImageDimension' => $this->getMaxImageDimension(),
            'minImageDimension' => $this->getMinImageDimension(),
          ]
        ];
    }
}