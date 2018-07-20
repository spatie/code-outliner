<?php

namespace Spatie\CodeOutline;

class Renderer
{
    /** @var array */
    protected $lines;

    public function __construct(array $lines)
    {
        $this->lines = $lines;
    }

    public function getRendered(): string
    {
        $lines = $this->renderLines();

        return str_replace('{{ outline }}', $lines, file_get_contents(__DIR__.'/index.twig'));
    }

    protected function renderLines()
    {
        $rendered = [];

        $maximumCharacterPositionDensity = $this->getMaximumCharacterPositionDensity($this->lines);

        foreach ($this->lines as $lineNumber => $line) {
            $lineNumber = str_pad($lineNumber, 3, '0', STR_PAD_LEFT);

            $rendered[] = $this->renderLine($line, $maximumCharacterPositionDensity, $lineNumber);
        }

        return implode(PHP_EOL, $rendered);
    }

    protected function renderLine(?array $line, int $maximumCharacterPositionDensity, string $lineNumber): string
    {
        if (!$line) {
            return "<div>{$lineNumber}: </div>";
        }

        $renderedLine = array_map(function ($characterValue) use ($maximumCharacterPositionDensity) {
            $class = 'code';

            if ($characterValue < 0) {
                $class .= ' indent';
            }

            $color = $this->getColor($characterValue, $maximumCharacterPositionDensity);

            return "<span class=\"{$class}\" style=\"background-color:{$color}\">&nbsp;</span>";
        }, $line);

        return "<div>{$lineNumber}: ".implode('', $renderedLine).'</div>';
    }

    protected function getMaximumCharacterPositionDensity(array $lines): int
    {
        $maximumDensityForLine = [];

        foreach ($lines as $line) {
            if (!$line) {
                continue;
            }

            $maximumDensityForLine[] = max($line);
        }

        return max($maximumDensityForLine);
    }

    protected function getColor(int $value, int $max): string
    {
        $modifier = 1 - ($value / $max);

        $gray = (245 * $modifier);

        return $this->getRgbHex($gray, $gray, $gray);
    }

    protected function getRgbHex(int $red, int $green, int $blue)
    {
        $colors = array_map(function ($color) {
            return str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
        }, [$red, $green, $blue]);

        return '#'.implode('', $colors);
    }
}
