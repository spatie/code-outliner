<?php

namespace Spatie\CodeOutline\Commands;

use Spatie\Browsershot\Browsershot;
use Spatie\CodeOutline\Elements\Page;
use Spatie\CodeOutline\Parser\DirectoryParser;
use Spatie\CodeOutline\Parser\FileParser;
use Spatie\CodeOutline\Parser\Parser;
use Spatie\CodeOutline\Renderer\Renderer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
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
        $page = $this->parseFiles($input, $output);

        $rendered = $this->renderParsed($page, $input, $output);

        $outputFilePath = $this->saveImage($rendered, $input, $output);

        $output->writeln("Saved to {$outputFilePath}");
    }

    protected function parseFiles(InputInterface $input, OutputInterface $output): Page
    {
        $path = $input->getArgument('path');

        $output->writeln('Parsing files...');

        $progressBar = new ProgressBar($output);

        $parser = $this->getParser($path, $input->getOption('extensions'), $progressBar);

        $progressBar->finish();

        return $parser->getParsed();
    }

    protected function renderParsed(Page $page, InputInterface $input, OutputInterface $output)
    {
        $output->writeln("\nRendering...");

        $renderer = new Renderer($page);

        return $renderer->getRendered();
    }

    protected function saveImage(string $rendered, InputInterface $input, OutputInterface $output): string
    {
        $output->writeln('Saving as image...');

        $outputFilePath = $this->getOutputFilePath($input->getOption('output'));

        Browsershot::html($rendered)->select('body')->save($outputFilePath);

        return $outputFilePath;
    }

    protected function getOutputFilePath(?string $path): string
    {
        return $path ?? './outline-code.png';
    }

    protected function getParser(string $path, ?string $extensions, ProgressBar $progressBar): Parser
    {
        if (is_dir($path)) {
            return (new DirectoryParser($path))
                ->setExtensionsFromString($extensions ?? 'php')
                ->onStartParsing(function (int $count) use ($progressBar) {
                    $progressBar->start($count);
                })
                ->onFileParsed(function () use ($progressBar) {
                    $progressBar->advance();
                });
        }

        return new FileParser($path);
    }
}
