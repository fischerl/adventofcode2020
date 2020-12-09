<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NineCommand extends Command
{
    protected static $defaultName = 'app:nine';

    private $sums = [];

    private $lines;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = __DIR__ . '/../../input/09/input.txt';
        $this->lines = explode("\n", file_get_contents($filePath));
        $result = null;
        $preCount = 25;
        $foundLine = null;
        foreach ($this->lines as $index => $number) {
            if ($index < $preCount) {
                continue;
            }
            $this->calculateSums($index, $preCount);
            if (!isset($this->sums[$number])) {
                $result = $number;
                $foundLine = $index;

            }
        }

        $output->writeln(sprintf("1: Answer is <info>%s</info>.", $result));

        $do = true;
        $start = 0;
        while ($do) {
            $sum = 0;
            $firstIndex = null;
            $length = null;
            for($i = $start; $i<$foundLine; $i++) {
                if (null === $firstIndex) {
                    $firstIndex = $i;
                    $length = 0;
                } else {
                    $length++;
                }
                $sum = $sum + $this->lines[$i];
                if ($sum == $result) {
                    $do = false;
                    $resultTwo = min(array_slice($this->lines, $firstIndex, $length))
                        + max(array_slice($this->lines, $firstIndex, $length));
                } else if ($sum < $result) {
                    continue;
                } else {
                    $firstIndex = null;
                    $start++;
                    $length = null;
                    break;
                }
            }
        }

        $output->writeln(sprintf("2: Answer is <info>%s</info>.", $resultTwo));


        return Command::SUCCESS;
    }

    private function calculateSums($index, $preCount)
    {
        $this->sums = [];
        for($i = $index-$preCount; $i<$index; $i++) {
            for($j = $index-$preCount; $j<$index; $j++) {
                if ($i != $j) {
                    $this->sums[intval($this->lines[$i]) + $this->lines[$j]] = 1;
                }
            }
        }
    }
}