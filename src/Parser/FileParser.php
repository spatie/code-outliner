<?php

namespace Spatie\CodeOutline\Parser;

use Spatie\CodeOutline\EmptyLine;
use Spatie\CodeOutline\Line;
use Spatie\CodeOutline\Page;

class FileParser implements Parser
{
    /** @var string */
    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getParsed(): Page
    {
        $contents = file_get_contents($this->path);

        $lines = explode(PHP_EOL, $contents);

        $page = new Page();

        foreach ($lines as $line) {
            if (!strlen($line)) {
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
