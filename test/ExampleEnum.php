<?php

declare(strict_types=1);

namespace test\Enum;

use Enum\Enum;

/**
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
