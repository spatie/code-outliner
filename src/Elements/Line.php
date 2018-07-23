<?php

namespace Spatie\CodeOutline\Elements;

use Spatie\Typed\Collection;
use Spatie\Typed\T;

class Line extends Collection
{
    public function __construct(int ...$values)
    {
        parent::__construct(T::int());

        $this->set($values);
    }

    public static function make(int $indentationCount, int $characterCount): self
    {
        $indentationValues = array_fill(0, $indentationCount, -1);

        $characterValues = array_fill(0, $characterCount, 1);

        $lineValues = array_merge($indentationValues, $characterValues);

        return new self(...$lineValues);
    }

    public function merge(self $line): self
    {
        $mergedLine = new self();

        $largestLine = $this->count() > $line->count()
            ? $this
            : $line;

        foreach ($largestLine as $position => $value) {
            $mergedLine[$position] = ($this[$position] ?? 0) + ($line[$position] ?? 0);
        }

        if (!$largestLine->count()) {
            return new EmptyLine();
        }

        return $mergedLine;
    }

    public function getMaximumCharacterPositionDensity(): int
    {
        return $this->count()
            ? max($this->data)
            : 0;
    }

    public function offsetGet($offset): int
    {
        return parent::offsetGet($offset);
    }
}
