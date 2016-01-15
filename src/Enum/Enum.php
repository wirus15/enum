<?php

namespace Enum;

abstract class Enum
{
    /**
     * @var mixed
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private static $items = [];

    /**
     * Enum constructor.
     * @param mixed $key
     * @param mixed $value
     */
    private function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get all enum items of particular enum class
     * @return Enum[]
     */
    public static function getItems()
    {
        $class = get_called_class();
        if (!isset(self::$items[$class])) {
            $values = self::getValuesMap($class);
            $items = [];
            foreach ($values as $key => $value) {
                $items[$key] = new $class($key, $value);
            }
            self::$items[$class] = $items;
        }

        return self::$items[$class];
    }

    /**
     * Returns an enum item with given key
     * @param mixed $key
     * @return Enum
     * @throws EnumException
     */
    public static function get($key)
    {
        $items = static::getItems();
        if (!isset($items[$key])) {
            throw new EnumException(sprintf(
                'Enum class %s does not have key: %s.',
                get_called_class(),
                $key
            ));
        }

        return $items[$key];
    }

    /**
     * Checks if this enum is contained in the given array of values
     * @param array $values
     * @return bool
     */
    public function in(array $values)
    {
        foreach ($values as $value) {
            if ($this->equals($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Compares enum object with given value.
     * @param mixed $value
     * @param boolean $strict Also checks the given value's type
     * @return boolean
     */
    public function equals($value, $strict = false)
    {
        if ($this === $value) {
            return true;
        }

        if ($strict && (!is_object($value) || get_class($this) !== get_class($value))) {
            return false;
        }

        if ($value instanceof self) {
            $value = $value->getValue();
        }

        return $this->getValue() == $value;
    }

    /**
     * @param string $class
     * @return array
     */
    private static function getValuesMap($class)
    {
        $reflection = new \ReflectionClass($class);
        $constants = $reflection->getConstants();

        return $constants;
    }
}