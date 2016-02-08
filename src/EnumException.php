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
     * @param string $key
     * @return EnumException
     */
    public static function invalidEnumKey($class, $key)
    {
        return new self(sprintf('Enum class %s does not have key: %s.', $class, $key));
    }
}