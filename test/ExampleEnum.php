<?php

namespace test\Enum;

use Enum\Enum;

/**
 * Class ExampleEnum
 * @package test\Enum
 * @method static string FOO()
 * @method static string BAR()
 * @method static string XYZ()
 */
class ExampleEnum extends Enum
{
    const FOO = 'foo';
    const BAR = 'bar';
    const XYZ = 'xyz';
}

class AnotherExampleEnum extends Enum
{
    const FOO = 'foo';
}