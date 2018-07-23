<?php

namespace Spatie\CodeOutline\Parser;

use Closure;
use Spatie\CodeOutline\Elements\EmptyLine;
use Spatie\CodeOutline\Elements\Line;
use Spatie\CodeOutline\Elements\Page;
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

    public function getParsed(): Page
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

    /**
     * @param \Spatie\CodeOutline\Elements\Page[] $pages
     *
     * @return \Spatie\CodeOutline\Elements\Page
     */
    protected function summarize(array $pages): Page
    {
        $summarizedPage = new Page();

        foreach ($pages as $page) {
            foreach ($page as $lineNumber => $line) {
                $summarizedLine = $summarizedPage[$lineNumber] ?? new EmptyLine();

                $summarizedPage[$lineNumber] = $summarizedLine->merge($line);
            }
        }

        return $summarizedPage;
    }

    protected function getExtensionsRegex(): string
    {
        $query = implode('|', array_map(function ($extension) {
            return "\.{$extension}\$";
        }, $this->extensions));

        return "/{$query}/";
    }
}
