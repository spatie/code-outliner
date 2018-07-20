<?php

namespace Spatie\CodeOutline\Parser;

use Spatie\CodeOutline\Elements\Page;

interface Parser
{
    public function getParsed(): Page;
}
