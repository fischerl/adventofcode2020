<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FirstCommand extends Command
{
    protected static $defaultName = 'app:first';

    protected function configure()
    {
        $this->addArgument('input', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('input');
        $content = explode("\n", file_get_contents($filePath));
        $content = array_map(function($string) {
            return intval($string);
        }, $content);
        $threeFound = false;
        $twoFound = false;

        foreach ($content as $numberOne) {
            if ($twoFound && $threeFound) {
                continue;
            }
            foreach ($content as $numberTwo) {
                if ($numberOne + $numberTwo == 2020) {
                    $result = $numberOne * $numberTwo;
                    $output->writeln(
                        "2: Numbers a <info>$numberOne</info> and <info>$numberTwo</info> - multiplied: <info>$result</info>"
                    );
                    $twoFound = true;
                }
                if ($threeFound) {
                    continue;
                }
                foreach ($content as $numberThree) {
                    if ($numberOne + $numberTwo + $numberThree == 2020) {
                        $result = $numberOne * $numberTwo * $numberThree;
                        $output->writeln(
                            "3: Numbers a <info>$numberOne</info> and <info>$numberTwo</info> and <info>$numberThree</info> - multiplied: <info>$result</info>"
                        );
                        $threeFound = true;
                    }
                }
            }
        }

        return Command::FAILURE;
    }
}