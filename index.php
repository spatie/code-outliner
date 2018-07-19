<?php

use Spatie\Outline\DirectoryParser;
use Spatie\Outline\FileParser;
use Spatie\Outline\Renderer;

require_once __DIR__ . '/vendor/autoload.php';

$path = $_GET['path'];

$extensions = $_GET['extensions'] ?? 'php';

$parser = is_dir($path)
    ? (new DirectoryParser($path))->setExtensionsFromString($extensions)
    : new FileParser($path);

$renderer = new Renderer($parser->getParsed());

echo $renderer->getRendered();
