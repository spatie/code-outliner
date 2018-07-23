<?php

namespace Spatie\CodeOutline\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\CodeOutline\Parser\FileParser;
use Spatie\CodeOutline\Renderer\Renderer;

class OutlinerTest extends TestCase
{
    /** @test */
    public function outline_renders_correctly()
    {
        $parser = new FileParser(__DIR__.'/data/outline.php');

        $renderer = new Renderer($parser->getParsed());

        $this->assertEquals(file_get_contents(__DIR__.'/data/output.html'), $renderer->getRendered());
    }
}
