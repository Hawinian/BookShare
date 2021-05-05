<?php

namespace App\Test\Service;

use App\Service\FizzBuzzService;
use PHPUnit\Framework\TestCase;

/**
 * Class FizzBuzzServiceTest.
 */
class FizzBuzzServiceTest extends TestCase
{
    public function testCountResult(): void
    {
        $expectedResultSize = 100;

        $fizzBuzz = new FizzBuzzService();

        $result = $fizzBuzz->execute();
        $this->assertSame($expectedResultSize, count($result));
    }

    public function testNumber()
    {
        $fizzBuzz = new FizzBuzzService();
        $result = $fizzBuzz->execute();
        $this->assertSame(1, $result[0]);
        $this->assertSame(7, $result[6]);
        $this->assertSame(26, $result[25]);
    }

    public function testFizzValue()
    {
        $fizzBuzz = new FizzBuzzService();
        $result = $fizzBuzz->execute();
        $this->assertSame('Fizz', $result[98]);
        $this->assertSame('Fizz', $result[65]);
        $this->assertSame('Fizz', $result[35]);
    }

    public function testBuzzValue()
    {
        $fizzBuzz = new FizzBuzzService();
        $result = $fizzBuzz->execute();
        $this->assertSame('Buzz', $result[9]);
        $this->assertSame('Buzz', $result[49]);
        $this->assertSame('Buzz', $result[79]);
    }

    public function testFizzBuzzValue()
    {
        $fizzBuzz = new FizzBuzzService();
        $result = $fizzBuzz->execute();
        $this->assertSame('FizzBuzz', $result[14]);
        $this->assertSame('FizzBuzz', $result[29]);
        $this->assertSame('FizzBuzz', $result[89]);
    }
}
