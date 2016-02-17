<?php

namespace Enum\Label;

use Enum\Enum;

interface LabelProvider
{
    public function getPrint(Enum $enum);
}