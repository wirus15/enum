<?php

declare(strict_types=1);

namespace Enum;

class EnumException extends \Exception
{
    public static function notValidEnumClass(string $class): self
    {
        return new self('Class '.$class.' is not a valid enum class.');
    }

    public static function invalidEnumValue(string $class, $value): self
    {
        return new self(sprintf('Enum class %s does not have value: %s.', $class, $value));
    }

    public static function notCloneable(string $class): self
    {
        return new self($class . ' is not cloneable.');
    }
}
