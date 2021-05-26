<?php
//
//namespace App\Tests\Controller;
//
//use Generator;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//
///**
// * Class FizzBuzzControllerTest.
// */
//class FizzBuzzControllerTest extends WebTestCase
//{
//    /**
//     * Test /fizz-buzz route.
//     */
//    public function testFizzBuzzRoute(): void
//    {
//        // given
//        $client = static::createClient();
//
//        // when
//        $client->request('GET', '/fizz-buzz');
//        $resultHttpStatusCode = $client->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals(200, $resultHttpStatusCode);
//    }
//
//    public function testDefaultFizzBuzz(): void
//    {
//        // given
//        $client = static::createClient();
//
//        // when
//        $client->request('GET', '/fizz-buzz');
//
//        // then
//        $this->assertSelectorTextContains('html title', 'FizzBuzz 1');
//        $this->assertSelectorTextContains('html h1', 'Your FizzBuzz is 1!');
//        $this->assertSelectorTextContains('html p', 'The number you chose was: 1');
//    }
//
//    /**
//     * @dataProvider dataProviderForTestPersonalizedFizzBuzz
//     */
//    public function testPersonalizedFizzBuzz(string $number, string $expectedtitle, string $expectedheader, string $expectedtext): void
//    {
//        // given
//        $client = static::createClient();
//
//        // when
//        $client->request('GET', '/fizz-buzz/'.$number);
//
//        // then
//        $this->assertSelectorTextContains('html title', $expectedtitle);
//        $this->assertSelectorTextContains('html h1', $expectedheader);
//        $this->assertSelectorTextContains('html p', $expectedtext);
//    }
//
//    /**
//     * @return \Generator Test case
//     */
//    public function dataProviderForTestPersonalizedFizzBuzz(): Generator
//    {
//        yield '2' => [
//            'number' => '2',
//            'expectedtitle' => 'FizzBuzz 2',
//            'expectedheader' => 'Your FizzBuzz is 2!',
//            'expectedtext' => 'The number you chose was: 2',
//        ];
//        yield '3' => [
//            'number' => '3',
//            'expectedtitle' => 'FizzBuzz Fizz',
//            'expectedheader' => 'Your FizzBuzz is Fizz!',
//            'expectedtext' => 'The number you chose was: 3',
//        ];
//        yield '4' => [
//            'number' => '4',
//            'expectedtitle' => 'FizzBuzz 4',
//            'expectedheader' => 'Your FizzBuzz is 4!',
//            'expectedtext' => 'The number you chose was: 4',
//        ];
//        yield '60' => [
//            'number' => '60',
//            'expectedtitle' => 'FizzBuzz FizzBuzz',
//            'expectedheader' => 'Your FizzBuzz is FizzBuzz!',
//            'expectedtext' => 'The number you chose was: 60',
//        ];
//        yield '33' => [
//            'number' => '33',
//            'expectedtitle' => 'FizzBuzz Fizz',
//            'expectedheader' => 'Your FizzBuzz is Fizz!',
//            'expectedtext' => 'The number you chose was: 33',
//        ];
//        yield '80' => [
//            'number' => '80',
//            'expectedtitle' => 'FizzBuzz Buzz',
//            'expectedheader' => 'Your FizzBuzz is Buzz!',
//            'expectedtext' => 'The number you chose was: 80',
//        ];
//        yield '97' => [
//            'number' => '97',
//            'expectedtitle' => 'FizzBuzz 97',
//            'expectedheader' => 'Your FizzBuzz is 97!',
//            'expectedtext' => 'The number you chose was: 97',
//        ];
//    }
//}
