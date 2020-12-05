<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SecondCommand extends Command
{
    protected static $defaultName = 'app:second';

    protected function configure()
    {
        $this->addArgument('input', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('input');
        $content = explode("\n", file_get_contents($filePath));
        $content = array_map(function($string) {
            $parts = explode(' ', $string);
            return $parts;
        }, $content);

        $countValidPws = 0;
        foreach ($content as $c) {
            $counts = explode('-', $c[0]);
            if (count($counts) !=2) {
                continue;
            }
            $char = str_split($c[1], 1)[0];
            $count = substr_count($c[2], $char);
            if ($count <= $counts[1] && $count >= $counts[0]) {
                $countValidPws++;
            }
        }
        $output->writeln(
            "1: Answer is <info>$countValidPws</info>."
        );
        $countValidPws = 0;
        foreach ($content as $c) {
            $positions = explode('-', $c[0]);
            if (count($positions) !=2) {
                continue;
            }
            $char = str_split($c[1], 1)[0];
            $wordsParts = str_split($c[2], 1);
            $firstIsSet = isset($wordsParts[$positions[0]-1]) && $wordsParts[$positions[0]-1] == $char;
            $secondIsSet = isset($wordsParts[$positions[1]-1]) && $wordsParts[$positions[1]-1] == $char;
            if ($firstIsSet && $secondIsSet) {
                continue;
            }

            if ($firstIsSet || $secondIsSet) {
                $countValidPws++;
            }
        }
        $output->writeln(
            "2: Answer is <info>$countValidPws</info>."
        );


        return Command::SUCCESS;
    }
}