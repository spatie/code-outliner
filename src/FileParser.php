<?php

namespace Spatie\CodeOutline;

class FileParser implements Parser
{
    /** @var string */
    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getParsed(): array
    {
        $contents = file_get_contents($this->path);

        $lines = explode(PHP_EOL, $contents);

        $outline = [];

        foreach ($lines as $line) {
            if (!strlen($line)) {
                $outline[] = null;

                continue;
            }

            $totalLineCount = strlen($line);

            $characterLineCount = strlen(ltrim($line));

            $indentLineCount = $totalLineCount - $characterLineCount;

            $indentLine = array_fill(0, $indentLineCount, -1);

            $characterLine = array_fill(0, $characterLineCount, 1);

            $outline[] = array_merge($indentLine, $characterLine);
        }

        return $outline;
    }
}
