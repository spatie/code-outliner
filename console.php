<?php

require __DIR__.'/vendor/autoload.php';

use Spatie\CodeOutline\Parser\Commands\OutlineFileCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new OutlineFileCommand());

$application->run();
