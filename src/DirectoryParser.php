<?php

namespace Spatie\Outline;

use Symfony\Component\Finder\Finder;

class DirectoryParser implements Parser
{
    /** @var string */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getParsed(): array
    {
        $parsed = [];

        $files = Finder::create()->files()->in($this->path)->name('*.php');

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
                    $flattened[$lineIndex][$cursorIndex] =  ($flattened[$lineIndex][$cursorIndex] ?? 0) + $characterValue;
                }
            }
        }

        return $flattened;
    }
}
