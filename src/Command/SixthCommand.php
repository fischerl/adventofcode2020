<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SixthCommand extends Command
{
    protected static $defaultName = 'app:sixth';


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = __DIR__ . '/../../input/06/input.txt';
        $content = explode("\n\n", file_get_contents($filePath));

        $counts = array_map(function ($group) {
            return count(array_unique(str_split(str_replace("\n", '', $group), 1)));
        }, $content);
        $output->writeln(sprintf("1: Answer is <info>%s</info>.", array_sum($counts)));

        $counts = array_map(function ($group) {
            $arr = null;
            $answers = explode("\n", $group);
            array_walk($answers, function($answer) use (&$arr) {
                if (null === $arr) {
                    $arr = str_split($answer, 1);
                }
                $arr = array_intersect($arr, str_split($answer, 1));
            });
            return count($arr);
        }, $content);
        $output->writeln(sprintf("2: Answer is <info>%s</info>.", array_sum($counts)));

        return Command::SUCCESS;
    }
}