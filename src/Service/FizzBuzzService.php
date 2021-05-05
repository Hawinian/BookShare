<?php

namespace App\Service;

/**
 * Class FizzBuzz.
 */
class FizzBuzzService
{
    public function execute(): array
    {
        $result = [];

        for ($i = 0; $i < 100; ++$i) {
            $result[$i] = ($i + 1);

            if ($this->dividableThree($result[$i]) && $this->dividableFive($result[$i])) {
                $result[$i] = 'FizzBuzz';
            } elseif ($this->dividableThree($result[$i])) {
                $result[$i] = 'Fizz';
            } elseif ($this->dividableFive($result[$i])) {
                $result[$i] = 'Buzz';
            }
        }

        return $result;
    }

    private function dividableThree(int $number): bool
    {
        return 0 === $number % 3;
    }

    private function dividableFive(int $number): bool
    {
        return 0 === $number % 5;
    }
}
