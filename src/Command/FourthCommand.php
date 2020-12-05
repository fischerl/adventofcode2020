<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FourthCommand extends Command
{
    protected static $defaultName = 'app:fourth';


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = __DIR__ . '/../../input/04/input.txt';
        $content = explode("\n", file_get_contents($filePath));
        $passports = [];
        $passport = [];
        foreach ($content as $c) {
            if (trim($c)) {
                $parts = explode(' ', $c);
                foreach ($parts as $p) {
                    $keyValue = explode(':', $p);
                    if (trim($keyValue[0]) && trim($keyValue[1])) {
                        $passport[trim($keyValue[0])] = trim($keyValue[1]);
                    }
                }
            } else {
                $passports[] = $passport;
                $passport = [];
            }
        }
        $passports[] = $passport;

        $mandatory = [
            'byr'=> function($value) {
                return strlen($value) == 4 && intval($value) >= 1920 && intval($value) <= 2002;
            },
            'iyr' => function($value) {
                return strlen($value) == 4 && intval($value) >= 2010 && intval($value) <= 2020;
            },
            'eyr' => function($value) {
                return strlen($value) == 4 && intval($value) >= 2020 && intval($value) <= 2030;
            },
            'hgt' => function($value) {
                preg_match('/^(\d{2,3})(\w{2})$/', $value, $matches);
                if (!isset($matches[2])) {
                    return false;
                }
                if ($matches[2] == 'cm') {
                    return intval($matches[1]) >= 150 && intval($matches[1]) <= 193;
                }
                if ($matches[2] == 'in') {
                    return intval($matches[1]) >= 59 && intval($matches[1]) <= 76;
                }
                return false;
            },
            'hcl' => function($value) {
                return preg_match('/^#([a-f0-9]{6})$/', $value);
            },
            'ecl' => function($value) {
                return in_array($value, ['amb', 'blu', 'brn', 'gry' ,'grn', 'hzl', 'oth']);
            },
            'pid' => function($value) {
                return preg_match('/^0*(\d{9})$/', $value);
            },
        ];
        $counter = 0;
        foreach ($passports as $passport) {
            $valid = true;
            foreach ($mandatory as $field => $isValidCallback) {
                if (!isset($passport[$field]) || !$isValidCallback($passport[$field])) {
                    $valid = false;
                }
            }
            if ($valid) {
                $counter++;
            }
        }
        $output->writeln(
            "1: Answer is <info>$counter</info>."
        );

        return Command::SUCCESS;
    }
}