<?php

namespace Spatie\CodeOutline\Parser;

use Spatie\CodeOutline\Elements\EmptyLine;
use Spatie\CodeOutline\Elements\Line;
use Spatie\CodeOutline\Elements\Page;
use Spatie\CodeOutline\Exceptions\FileNotFound;

class FileParser implements Parser
{
    /** @var string */
    protected $path;

    public function __construct(string $path)
    {
        if (! file_exists($path)) {
            throw FileNotFound::path($path);
        }

        $this->path = $path;
    }

    public function getParsed(): Page
    {
        $contents = file_get_contents($this->path);

        $lines = explode(PHP_EOL, $contents);

        $page = new Page();

        foreach ($lines as $line) {
            if (strlen($line) === 0) {
                $page[] = new EmptyLine();

                continue;
            }

            $totalLineCount = strlen($line);

            $characterCount = strlen(ltrim($line));

            $indentationCount = $totalLineCount - $characterCount;

            $page[] = Line::make($indentationCount, $characterCount);
        }

        return $page;
    }
}
