<?php

namespace App;

/**
 * Class StringCalculator.
 */
class StringCalculator
{
    public function add(string $input): int
    {
        if ('' == $input) {
            return 0;
        } elseif ('' != $input) {
            $input = str_replace('\n', ',', $input);
            $numbers = explode(',', $input);

            $result = [];
            foreach ($numbers as $eachNumber) {
                $result[] = (int) $eachNumber;
            }

            return array_sum($result);
        }
    }
}
