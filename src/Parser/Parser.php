<?php

namespace Spatie\CodeOutline\Parser;

use Spatie\CodeOutline\Page;

interface Parser
{
    public function getParsed(): Page;
}
