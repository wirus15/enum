<?php

namespace Enum;

abstract class Enum
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private static $constants = [];

    /**
     * @var array
     */
    private static $items = [];

    /**
     * Enum constructor.
     * @param string $key
     * @param mixed $value
     */
    private function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Returns enum key - constant name
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Returns enum value - constant value
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Get all enum items of particular enum class
     * @param string|null $class
     * @return \static[]|Enum[]
     * @throws EnumException
     */
    public static function all($class = null)
    {
        $class = $class ?: get_called_class();

        if (isset(self::$items[$class])) {
            return self::$items[$class];
        }

        $constants = self::constants($class);
        $items = [];
        foreach ($constants as $key => $value) {
            $items[$value] = new $class($key, $value);
        }

        return self::$items[$class] = $items;
    }

    /**
     * Returns an enum item with given value
     * @param mixed $value
     * @param string|null $class
     * @return \static|Enum
     * @throws EnumException
     */
    public static function get($value, $class = null)
    {
        if ($value instanceof self) {
            $value = $value->value();
        }

        if (!static::has($value, $class)) {
            throw EnumException::invalidEnumValue($class, $value);
        }

        return static::all($class)[$value];
    }

    /**
     * Tells if the enum contains a particular value
     * @param mixed $value
     * @param string|null $class
     * @return bool
     */
    public static function has($value, $class = null)
    {
        return array_key_exists($value, static::all($class));
    }

    /**
     * Compares enum object with given value.
     * @param mixed $value
     * @param boolean $strict Also checks the given value's type
     * @return boolean
     */
    public function is($value, $strict = false)
    {
        if ($this === $value) {
            return true;
        }

        if ($strict && (!is_object($value) || get_class($this) !== get_class($value))) {
            return false;
        }

        if ($value instanceof self) {
            $value = $value->value();
        }

        return $this->value() == $value;
    }

    /**
     * Checks if this enum is contained in the given array of values
     * @param array $values
     * @return bool
     */
    public function in(array $values)
    {
        foreach ($values as $value) {
            if ($this->is($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns all enum keys of particular class
     * @param string|null $class
     * @return string[]
     * @throws EnumException
     */
    public static function keys($class = null)
    {
        return array_keys(static::constants($class));
    }

    /**
     * Returns all enum values of particular class
     * @param string|null $class
     * @return array
     * @throws EnumException
     */
    public static function values($class = null)
    {
        return array_values(static::constants($class));
    }

    /**
     * Returns label
     */
    public function __toString()
    {
        return $this->key;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return \static|Enum
     * @throws EnumException
     */
    public static function __callStatic($name, $arguments)
    {
        $constants = static::constants();

        if (!array_key_exists($name, $constants)) {
            throw EnumException::invalidEnumValue(get_called_class(), $name);
        }

        return static::get($constants[$name]);
    }

    /**
     * @throws EnumException
     */
    public function __clone()
    {
        throw EnumException::notCloneable(get_class($this));
    }

    /**
     * Returns constants map of given class
     * @param string $class
     * @return array
     * @throws EnumException
     */
    private static function constants($class = null)
    {
        $class = $class ?: get_called_class();

        if (isset(self::$constants[$class])) {
            return self::$constants[$class];
        }

        if (!is_subclass_of($class, self::class)) {
            throw EnumException::notValidEnumClass($class);
        }

        $reflection = new \ReflectionClass($class);

        return self::$constants[$class] = $reflection->getConstants();
    }
}