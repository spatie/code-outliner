<?php

namespace Spatie\CodeOutline\Elements;

use Spatie\Typed\Collection;
use Spatie\Typed\T;

class Page extends Collection
{
    public function __construct()
    {
        parent::__construct(T::generic(Line::class));
    }

    public function getMaximumCharacterPositionDensity(): int
    {
        $maximumDensityForLine = [];

        foreach ($this as $line) {
            $maximumDensityForLine[] = $line->getMaximumCharacterPositionDensity();
        }

        return max($maximumDensityForLine);
    }

    public function offsetGet($offset): Line
    {
        return parent::offsetGet($offset);
    }
}
