<?php

namespace Enum\Printer;

use Enum\Enum;

class SimpleEnumPrinter implements EnumPrinter
{
    public function getPrint(Enum $enum)
    {
        return ucwords(strtolower(str_replace('_', ' ', $enum->key())));
    }
}