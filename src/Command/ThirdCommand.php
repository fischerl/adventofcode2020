<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ThirdCommand extends Command
{
    protected static $defaultName = 'app:third';


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = __DIR__ . '/../../input/03/input.txt';
        $content = explode("\n", file_get_contents($filePath));
        $array = array_map(function($string) {
            return str_split($string, 1);
        }, $content);

        $startI = 1;
        $startJ = 3;

        $countTrees = $this->slope($array, $startI, $startJ, $output);
        $output->writeln(
            "1: Answer is <info>$countTrees</info>."
        );
        $configs = [[1,1], [3,1], [5,1], [7,1], [1,2]];

        $result = 1;
        foreach ($configs as $config) {
            $result *= $this->slope($array, $config[1], $config[0], $output);
        }


        $output->writeln(
            "2: Answer is <info>$result</info>."
        );

        return Command::SUCCESS;
    }

    private function slope(array $array, int $startI, int $startJ, $output): int
    {
        $i = $startI;
        $j = $startJ;
        $countTrees = 0;
        $end = false;
        while ($end == false) {
            if ($array[$i][$j] == '#') {
                $countTrees++;
            }
            $string = '';
            foreach ($array[$i] as $index => $char) {
                if ($index == $j) {
                    $string .= "<error>$char</error>";
                    continue;
                }
                $string .= $char;
            }
            $output->writeln($string);
            $i+=$startI;
            $j+=$startJ;
            if (isset($array[$i]) && !isset($array[$i][$j])) {
                for($start = $startJ-1; $start >=0; $start--) {
                    if (isset($array[$i][$j-($startJ-$start)])) {
                        $j = ($startJ-$start-1);
                        continue 2;
                    }
                }
                continue;
            }

            if (!isset($array[$i])) {
                $end = true;
            }
        }
        return $countTrees;
    }
}