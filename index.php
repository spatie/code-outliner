<?php

use Spatie\CodeOutline\Parser\DirectoryParser;
use Spatie\CodeOutline\Parser\FileParser;
use Spatie\CodeOutline\Renderer\Renderer;

require_once __DIR__.'/vendor/autoload.php';

$path = $_GET['path'];

$extensions = $_GET['extensions'] ?? 'php';

$parser = is_dir($path)
    ? (new DirectoryParser($path))->setExtensionsFromString($extensions)
    : new FileParser($path);

$renderer = new Renderer($parser->getParsed());

echo $renderer->getRendered();
