<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EightCommand extends Command
{
    protected static $defaultName = 'app:eight';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = __DIR__ . '/../../input/08/input.txt';
        $lines = explode("\n", file_get_contents($filePath));
        $acc = 0;
        $i = 0;
        $visited = [];
        $continue = true;
        while ($continue && isset($lines[$i]) && !isset($visited[$i])) {
            if (preg_match('/^(\w{3})\s([+,-])(\d+)$/', $lines[$i], $matches)) {
                $visited[$i] = 1;
                $operator = $matches[2];
                $int = intval($matches[3]);
                switch ($matches[1]) {
                    case 'jmp':
                        $i = eval("return $i$operator$int;");
                        break;
                    case 'acc':
                        $acc = eval("return $acc$operator$int;");
                        $i++;
                        break;
                    default:
                        $i++;
                        break;
                }
            } else {
                $continue = false;
            }
        }


        $output->writeln(sprintf("1: Answer is <info>%s</info>.", $acc));

        $acc = 0;
        $i = 0;
        $continue = true;
        $changed = [];
        $visited = [];
        $changedPerRound = false;
        while ($continue && isset($lines[$i])) {
            if (isset($visited[$i])) {
                $i = 0;
                $acc = 0;
                $visited = [];
                $changedPerRound = false;
            }
            if (preg_match('/^(\w{3})\s([+,-])(\d+)$/', $lines[$i], $matches)) {

                $visited[$i] = true;
                $operator = $matches[2];
                $int = intval($matches[3]);
                switch ($matches[1]) {
                    case 'jmp':
                        if (!$changedPerRound && !isset($changed[$i])) {
                            $changedPerRound = true;
                            $changed[$i] = 1;
                            $i++;
                        } else {
                            $i = eval("return $i$operator$int;");
                        }
                        break;
                    case 'acc':
                        $acc = eval("return $acc$operator$int;");
                        $i++;
                        break;
                    default:
                        if (!$changedPerRound && !isset($changed[$i])) {
                            $changedPerRound = true;
                            $changed[$i] = 1;
                            $i = eval("return $i$operator$int;");
                        } else {
                            $i++;
                        }
                        break;
                }
            } else {
                $continue = false;
            }
        }

        $output->writeln(sprintf("2: Answer is <info>%s</info>.", $acc));

        return Command::SUCCESS;
    }
}