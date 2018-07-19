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

        $this->addOption('extensions', null, InputOption::VALUE_OPTIONAL, 'The extensions of which files to scan for. Eg. `php,html`');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');

        $parser = $this->getParser($path, $input->getOption('extensions'));

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

    private function getParser(string $path, ?string $extensions): Parser
    {
        if (is_dir($path)) {
            return (new DirectoryParser($path))->setExtensionsFromString($extensions ?? 'php');
        }

        return new FileParser($path);
    }
}
