<?php

namespace App\Tests;

use App\StringCalculator;
use PHPUnit\Framework\TestCase;

/**
 * Class StringCalculatorTest.
 */
class StringCalculatorTest extends TestCase
{
    public function testEmptyString()
    {
        $stringCalculator = new StringCalculator();

        $expectedInput = '';
        $expectedResult = 0;
        $result = $stringCalculator->add($expectedInput);
        $this->assertSame($expectedResult, $result, 'For empty string return 0');
    }

    public function testSimpleAdd()
    {
        $stringCalculator = new StringCalculator();

        $expectedInput = '4,3';
        $expectedResult = 7;
        $result = $stringCalculator->add($expectedInput);
        $this->assertSame($expectedResult, $result, 'For 4+3 return 7');

        $expectedInput = '8,23';
        $expectedResult = 31;
        $result = $stringCalculator->add($expectedInput);
        $this->assertSame($expectedResult, $result, 'For 8+23 return 31');
    }

    public function testUnknownAmountOfNumbers()
    {
        $stringCalculator = new StringCalculator();

        $expectedInput = '4,3,32,21';
        $expectedResult = 60;
        $result = $stringCalculator->add($expectedInput);
        $this->assertSame($expectedResult, $result, 'For 4+3+32+21 should return 60');

        $expectedInput = '1,2,43,97,7,10';
        $expectedResult = 160;
        $result = $stringCalculator->add($expectedInput);
        $this->assertSame($expectedResult, $result, 'For 1+2+43+97+7+10 should return 160');
    }

    public function testNewLinesAsSeparators()
    {
        $stringCalculator = new StringCalculator();

        $expectedInput = '1\n2,3,8';
        $expectedResult = 14;
        $result = $stringCalculator->add($expectedInput);
        $this->assertSame($expectedResult, $result, 'For 1+2+3+8 should return 14');

        $expectedInput = '1\n2,3,5\n6';
        $expectedResult = 17;
        $result = $stringCalculator->add($expectedInput);
        $this->assertSame($expectedResult, $result, 'For 1+2+3+5+6 should return 17');
    }
}
