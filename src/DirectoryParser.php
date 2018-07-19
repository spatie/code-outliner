<?php

namespace Spatie\Outline;

use Symfony\Component\Finder\Finder;

class DirectoryParser implements Parser
{
    /** @var string */
    private $path;

    /** @var array */
    private $extensions = ['php'];

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function setExtensions(array $extensions): DirectoryParser
    {
        $this->extensions = $extensions;

        return $this;
    }

    public function setExtensionsFromString(string $extensionsString): DirectoryParser
    {
        $extensions = explode(',', $extensionsString);

        return $this->setExtensions(
            array_map(function (string $extension) {
                return str_replace(['*', '.'], '', $extension);
            }, $extensions)
        );
    }

    public function getParsed(): array
    {
        $parsed = [];

        $files = Finder::create()->files()->in($this->path)->name($this->getExtensionsSearchQuery());

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($files as $file) {
            $fileParser = new FileParser($file->getRealPath());

            $parsed[] = $fileParser->getParsed();
        }

        return $this->flatten($parsed);
    }

    private function flatten(array $pages): array
    {
        $flattened = [];

        foreach ($pages as $page) {
            foreach ($page as $lineIndex => $line) {
                if (! $line) {
                    $flattened[$lineIndex] = $flattened[$lineIndex] ?? null;

                    continue;
                }

                foreach ($line as $cursorIndex => $characterValue) {
                    $flattened[$lineIndex][$cursorIndex] = ($flattened[$lineIndex][$cursorIndex] ?? 0) + $characterValue;
                }
            }
        }

        return $flattened;
    }

    private function getExtensionsSearchQuery(): string
    {
        $query = implode('|', array_map(function ($extension) {
            return "\.{$extension}\$";
        }, $this->extensions));

        return "/{$query}/";
    }
}
