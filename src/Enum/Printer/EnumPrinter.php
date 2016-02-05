<?php

namespace Enum\Printer;

use Enum\Enum;

interface EnumPrinter
{
    public function getPrint(Enum $enum);
}