<?php

namespace Enum\Printer;

use Enum\Enum;

class SimpleEnumPrinterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SimpleEnumPrinter
     */
    private $sut;

    protected function setUp()
    {
        $this->sut = new SimpleEnumPrinter();
    }

    /**
     * @param $key
     * @param $expected
     * @dataProvider printProvider
     */
    public function testPrint($key, $expected)
    {
        $mock = \Mockery::mock(Enum::class, ['getKey' => $key]);
        $result = $this->sut->getPrint($mock);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function printProvider()
    {
        return [
            ['FOO', 'Foo'],
            ['FOO_BAR', 'Foo Bar'],
            ['yolo_swaggins', 'Yolo Swaggins'],
            ['A_B_C_D_E', 'A B C D E'],
            ['AA_BB_CC_DD', 'Aa Bb Cc Dd'],
            ['', ''],
            ['12345', '12345']
        ];
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}