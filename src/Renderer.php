<?php

namespace Spatie\CodeOutline;

class Renderer
{
    /** @var \Spatie\CodeOutline\Page */
    protected $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function getRendered(): string
    {
        $rendered = $this->renderPage();

        return str_replace('{{ outline }}', $rendered, file_get_contents(__DIR__.'/index.twig'));
    }

    protected function renderPage()
    {
        $rendered = [];

        $maximumCharacterPositionDensity = $this->page->getMaximumCharacterPositionDensity();

        foreach ($this->page as $lineNumber => $line) {
            $lineNumber = str_pad($lineNumber, 3, '0', STR_PAD_LEFT);

            $rendered[] = $this->renderLine($line, $maximumCharacterPositionDensity, $lineNumber);
        }

        return implode(PHP_EOL, $rendered);
    }

    protected function renderLine(?Line $line, int $maximumCharacterPositionDensity, string $lineNumber): string
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
        }, $line->toArray());

        return "<div>{$lineNumber}: ".implode('', $renderedLine).'</div>';
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
