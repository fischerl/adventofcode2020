<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeventhCommand extends Command
{
    protected static $defaultName = 'app:seventh';

    private $content;

    private $colors;

    private $sum = 0;


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = __DIR__ . '/../../input/07/input.txt';
        $this->content = explode("\n", file_get_contents($filePath));

        $this->colors = [];
        $this->findParentColors('shiny gold');

        $output->writeln(sprintf("1: Answer is <info>%s</info>.", count(array_unique($this->colors))));

        $this->colors = [];
        $this->findChildColors('shiny gold');
        $output->writeln(sprintf("2: Answer is <info>%s</info>.", $this->sum));

        return Command::SUCCESS;
    }

    private function findChildColors(string $color, int $count = 1)
    {
        foreach ($this->content as $line) {
            if (preg_match('/^(\w+\s\w+)\sbag[s]{0,1}\scontain\s(.*)\.$/', $line, $m)) {
                if ($m[1] == $color) {
                    $parts = explode(',', $m[2]);
                    foreach ($parts as $part) {
                        if (preg_match('/^\s*(\d)\s(\w+\s\w+)\sbag.*$/', $part, $matches)) {
                            $this->sum += $matches[1] * $count;
                            $this->findChildColors($matches[2], $matches[1] * $count);
                        }
                    }
                }
            }
        }
    }

    private function findParentColors(string $color)
    {
        foreach ($this->content as $line) {
            if (preg_match('/^(\w+\s\w+)\sbag[s]{0,1}\scontain\s(.*)\.$/', $line, $m)) {
                $parts = explode(',', $m[2]);
                foreach ($parts as $part) {
                    if (preg_match('/^\s*(\d)\s(\w+\s\w+)\sbag.*$/', $part, $matches)) {
                        if ($matches[2] == $color) {
                            $this->colors[] = $m[1];
                            $this->findParentColors($m[1]);
                        }
                    }
                }
            }
        }
    }
}