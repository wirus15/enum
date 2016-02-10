<?php

namespace test\Enum;

use Enum\Enum;
use Enum\Printer\EnumPrinter;

class UppercasePrinter implements EnumPrinter
{
    public function getPrint(Enum $enum)
    {
        return strtoupper($enum->key());
    }
}