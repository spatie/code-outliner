<?php

namespace Spatie\Outline\Commands;

use Spatie\Outline\DirectoryParser;
use Spatie\Outline\Parser;
use Spatie\Outline\Renderer;
use Spatie\Outline\FileParser;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class OutlineFileCommand extends Command
{
    public function __construct()
    {
        parent::__construct('outline');

        $this->addArgument('path', InputArgument::REQUIRED);

        $this->addOption('output', null, InputOption::VALUE_OPTIONAL, 'Where to save the output file (it must be a PNG).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');

        $parser = $this->getParser($path);

        $renderer = new Renderer($parser->getParsed());

        $outputFilePath = $this->getOutputFilePath($input->getOption('output'));

        Browsershot::html($renderer->getRendered())->select('body')->save($outputFilePath);

        $output->writeln("Saved to {$outputFilePath}");
    }

    private function getOutputFilePath(?string $path): string
    {
        if ($path) {
            return $path;
        }

        return './outline-code.png';
    }

    private function getParser(string $path): Parser
    {
        if (is_dir($path)) {
            return new DirectoryParser($path);
        }

        return new FileParser($path);
    }
}
