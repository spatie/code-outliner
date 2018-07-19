<?php

namespace Spatie\Outline;

class Renderer
{
    /** @var array */
    private $lines;

    public function __construct(array $lines)
    {
        $this->lines = $lines;
    }

    public function getRendered(): string
    {
        $lines = $this->renderLines();

        return str_replace('{{ outline }}', $lines, file_get_contents(__DIR__ . '/index.twig'));
    }

    private function renderLines()
    {
        $rendered = [];

        $max = $this->getMaxCharacterValue($this->lines);

        foreach ($this->lines as $lineNumber => $line) {
            $lineNumber = str_pad($lineNumber, 3, '0', STR_PAD_LEFT);

            $rendered[] = $this->renderLine($line, $max, $lineNumber);
        }

        return implode(PHP_EOL, $rendered);
    }

    private function renderLine(?array $line, int $max, string $lineNumber): string
    {
        if (! $line) {
            return "<div>{$lineNumber}: </div>";
        }

        $renderedLine = array_map(function ($characterValue) use ($max) {
            $class = 'code';

            if ($characterValue < 0) {
                $class .= ' indent';
            }

            $color = $this->getColor($characterValue, $max);

            return "<span class=\"{$class}\" style=\"background-color:{$color}\">&nbsp;</span>";
        }, $line);

        return "<div>{$lineNumber}: " . implode('', $renderedLine) . '</div>';
    }

    private function getMaxCharacterValue(array $lines): int
    {
        $maxPerLine = [];

        foreach ($lines as $line) {
            if (! $line) {
                continue;
            }

            $maxPerLine[] = max($line);
        }

        return max($maxPerLine);
    }

    private function getColor(int $value, int $max): string
    {
        $modifier = 1 -  ($value / $max);

        $gray = (245 * $modifier);

        return $this->getRgbHex($gray, $gray, $gray);
    }

    private function getRgbHex(int $r, int $g, int $b)
    {
        $colors = array_map(function ($color) {
            return str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
        }, [$r, $g, $b]);

        return '#' . implode('', $colors);
    }
}
