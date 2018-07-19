<?php

namespace Spatie\Outline;

use Closure;
use Symfony\Component\Finder\Finder;

class DirectoryParser implements Parser
{
    /** @var string */
    protected $path;

    /** @var array */
    protected $extensions = ['php'];

    /** @var Closure */
    protected $initListener;

    /** @var Closure */
    protected $progressListener;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function setExtensions(array $extensions): self
    {
        $this->extensions = $extensions;

        return $this;
    }

    public function setExtensionsFromString(string $extensionsString): self
    {
        $extensions = explode(',', $extensionsString);

        return $this->setExtensions(
            array_map(function (string $extension) {
                return str_replace(['*', '.'], '', $extension);
            }, $extensions)
        );
    }

    public function onStartParsing(Closure $initListener): self
    {
        $this->initListener = $initListener;

        return $this;
    }

    public function onFileParsed(Closure $progressListener): self
    {
        $this->progressListener = $progressListener;

        return $this;
    }

    public function getParsed(): array
    {
        $parsedFiles = [];

        $files = Finder::create()->files()->in($this->path)->name($this->getExtensionsRegex());

        if ($this->initListener) {
            call_user_func_array($this->initListener, [$files->count()]);
        }

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($files as $file) {
            $fileParser = new FileParser($file->getRealPath());

            $parsedFiles[] = $fileParser->getParsed();

            if (!$this->progressListener) {
                continue;
            }

            call_user_func($this->progressListener);
        }

        return $this->summarize($parsedFiles);
    }

    protected function summarize(array $parsedFiles): array
    {
        $summarizedFiles = [];

        foreach ($parsedFiles as $parsedFile) {
            foreach ($parsedFile as $lineIndex => $line) {
                if (!$line) {
                    $summarizedFiles[$lineIndex] = $summarizedFiles[$lineIndex] ?? null;

                    continue;
                }

                foreach ($line as $cursorIndex => $characterValue) {
                    $summarizedFiles[$lineIndex][$cursorIndex] = ($summarizedFiles[$lineIndex][$cursorIndex] ?? 0) + $characterValue;
                }
            }
        }

        return $summarizedFiles;
    }

    protected function getExtensionsRegex(): string
    {
        $query = implode('|', array_map(function ($extension) {
            return "\.{$extension}\$";
        }, $this->extensions));

        return "/{$query}/";
    }
}
