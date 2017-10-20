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
     *
     * @param string $key
     * @param mixed $value
     */
    private function __construct(string $key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Returns enum key - constant name
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * Returns enum value - constant value
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Get all enum items of particular enum class
     *
     * @return \static[]
     */
    public static function all(): array
    {
        $class = get_called_class();

        if (isset(self::$items[$class])) {
            return self::$items[$class];
        }

        $constants = static::constants();

        $items = [];

        foreach ($constants as $key => $value) {
            $items[$value] = new $class($key, $value);
        }

        return self::$items[$class] = $items;
    }

    /**
     * Returns enum instances only with given values
     *
     * @param array $values
     * @return \static[]
     * @throws EnumException
     */
    public static function only(array $values): array
    {
        $items = [];

        foreach ($values as $value) {
            $item = static::get($value);
            $items[$item->value()] = $item;
        }

        return $items;
    }

    /**
     * Returns an enum item with given value
     *
     * @param mixed $value
     * @return \static
     * @throws EnumException
     */
    public static function get($value): self
    {
        $class = get_called_class();

        if ($value instanceof self) {
            $value = $value->value();
        }

        if (!static::has($value)) {
            throw EnumException::invalidEnumValue($class, $value);
        }

        return static::all()[$value];
    }

    /**
     * Tells if the enum contains a particular value
     *
     * @param mixed $value
     * @return bool
     */
    public static function has($value): bool
    {
        return array_key_exists($value, static::all());
    }

    /**
     * Compares enum object with given value.
     *
     * @param mixed $value
     * @param boolean $strict Also checks the given value's type
     * @return boolean
     */
    public function is($value, bool $strict = false): bool
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
     *
     * @param array $values
     * @return bool
     */
    public function in(array $values): bool
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
     *
     * @return string[]
     */
    public static function keys(): array
    {
        return array_keys(static::constants());
    }

    /**
     * Returns all enum values of particular class
     *
     * @return array
     */
    public static function values(): array
    {
        return array_values(static::constants());
    }

    /**
     * Returns label
     */
    public function __toString(): string
    {
        return (string)$this->value();
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
     *
     * @return array
     */
    private static function constants(): array
    {
        $class = get_called_class();

        if (isset(self::$constants[$class])) {
            return self::$constants[$class];
        }

        $reflection = new \ReflectionClass($class);

        return self::$constants[$class] = $reflection->getConstants();
    }
}
