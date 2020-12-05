<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FifthCommand extends Command
{
    protected static $defaultName = 'app:fifth';


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = __DIR__ . '/../../input/05/input.txt';
        $content = explode("\n", file_get_contents($filePath));
        $max = 0;
        $seats = [];
        array_walk($content, function ($string) use (&$max, &$seats) {
            $id = bindec(
                str_replace('B', '1', str_replace('F', '0', substr($string, 0, 7))))
                * 8
                + bindec(
                    str_replace('R', '1', str_replace('L', '0', substr($string, 7, 3)))
                );
            $seats[$id] = $id;
            if ($id > $max) {
                $max = $id;
            }
        });
        $output->writeln("1: Answer is <info>$max</info>.");

        $mine = array_filter($seats, function ($seatId) use ($seats){
            return !isset($seats[$seatId+1]) && isset($seats[$seatId+2]);
        });
        $output->writeln(sprintf('2: Answer is <info>%s</info>.', array_pop($mine) + 1));

        return Command::SUCCESS;
    }
}