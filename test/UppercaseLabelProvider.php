<?php

namespace test\Enum;

use Enum\Enum;
use Enum\Label\LabelProvider;

class UppercaseLabelProvider implements LabelProvider
{
    public function getPrint(Enum $enum)
    {
        return strtoupper($enum->key());
    }
}