<?php

namespace spec\Enum\Printer;

use Enum\Enum;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SimpleEnumPrinterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Enum\Printer\SimpleEnumPrinter');
    }

    function it_shuould_print_enum(Enum $enum)
    {
        $prints = [
            'FOO' => 'Foo',
            'FOO_BAR' => 'Foo Bar',
            'yolo_swaggins' => 'Yolo Swaggins',
            'A_B_C_D_E' => 'A B C D E',
            'AA_BB_CC_DD' => 'Aa Bb Cc Dd',
            '' => '',
            '12345' => '12345'
        ];

        foreach ($prints as $from => $to) {
            $enum->getKey()->willReturn($from);
            $this->getPrint($enum)->shouldBe($to);
        }
    }
}
