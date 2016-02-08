<?php

namespace Enum;

class EnumException extends \Exception
{
    /**
     * @param string $class
     * @return EnumException
     */
    public static function notValidEnumClass($class)
    {
        return new self('Class '.$class.' is not a valid enum class.');
    }

    /**
     * @param string $class
     * @param string $value
     * @return EnumException
     */
    public static function invalidEnumValue($class, $value)
    {
        return new self(sprintf('Enum class %s does not have value: %s.', $class, $value));
    }

    /**
     * @param string $class
     * @return EnumException
     */
    public static function notCloneable($class)
    {
        return new self($class . ' is not cloneable.');
    }
}