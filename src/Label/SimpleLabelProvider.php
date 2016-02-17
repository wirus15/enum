<?php

namespace Enum\Label;

use Enum\Enum;

class SimpleLabelProvider implements LabelProvider
{
    public function getPrint(Enum $enum)
    {
        return ucwords(strtolower(str_replace('_', ' ', $enum->key())));
    }
}