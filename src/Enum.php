<?php

namespace Enum;

use Enum\Printer\EnumPrinter;
use Enum\Printer\SimpleEnumPrinter;

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
     * @var EnumPrinter
     */
    private static $printer;

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
     * @param string|null $class
     * @return Enum[]
     * @throws EnumException
     */
    public static function getItems($class = null)
    {
        $class = $class ?: get_called_class();

        if (!is_subclass_of($class, self::class)) {
            throw EnumException::notValidEnumClass($class);
        }

        if (!isset(self::$items[$class])) {
            $values = self::getValuesMap($class);
            $items = [];
            foreach ($values as $key => $value) {
                $items[$value] = new $class($key, $value);
            }
            self::$items[$class] = $items;
        }

        return self::$items[$class];
    }

    /**
     * Returns an enum item with given key
     * @param mixed $key
     * @param string|null $class
     * @return Enum
     * @throws EnumException
     */
    public static function get($key, $class = null)
    {
        $class = $class ?: get_called_class();
        if (!is_subclass_of($class, self::class)) {
            throw EnumException::notValidEnumClass($class);
        }

        $items = self::getItems($class);
        if (!isset($items[$key])) {
            throw EnumException::invalidEnumKey($class, $key);
        }

        return $items[$key];
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
     * Registers new enum printer
     * @param EnumPrinter $printer
     */
    public static function registerPrinter(EnumPrinter $printer = null)
    {
        self::$printer = $printer;
    }

    /**
     * Converts enum object to string using currently registered printer.
     */
    public function __toString()
    {
        static $defaultPrinter;

        if ($defaultPrinter === null) {
            $defaultPrinter = new SimpleEnumPrinter();
        }

        $printer = self::$printer ?: $defaultPrinter;

        return $printer->getPrint($this);
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