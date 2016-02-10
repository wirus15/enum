# Enum
A simple PHP library for creating enum objects.

## Examples

```php
use Enum\Enum;

// defining our enum class
final class Example extends Enum
{
    const FOO = 1;
    const BAR = 2;
    const YOLO = 3;
}

// getting a single enum instance
Example::get(Example::FOO);               // instance of Example
Example::FOO();                           // static call
Enum::get(Example::FOO, Example::class);  // specified enum class

// getting a list of instances
Example::all();                           // array of Example instances
Enum::all(Example::class);                // same as above

// keys and values
Example::keys();                          // array of keys ['FOO', 'BAR', 'YOLO']
Enum::keys(Example::class);               // same as above

Example::values();                        // array of values [1, 2, 3]
Enum::values(Example::class);             // same as above

// checking if value exists
Example::has(3);                          // true
Enum::has(1, Example::class);             // true
Example::has(4);                          // false

// Single enum properties
$foo = Example::FOO();
$foo->key();                              // 'FOO'
$foo->value();                            // 1

// comparing enums
$foo = Example::FOO();
$foo->is(1);                              // true
$foo->is(Example::FOO);                   // true
$foo->is($foo);                           // true

// comparing with strict option
$foo->is($foo, true);                     // true
$foo->is(1, true);                        // false

// searching in array
$foo->in([1,2,3]);                        // true
$foo->in([2,3]);                          // false
```

## Printers
Enum objects can be casted to string easily. By default it is done by formatting the enum's key. For example:
```php
class AnotherExample extends Enum 
{
    const FOO_BAR_YOLO = 'fby';
}

$fby = AnotherExample::get('fby');
echo (string)$fby;                        // 'Foo Bar Yolo'

You can register your own printer to customize:
```php
class ValuePrinter implements EnumPrinter
{
    public function getPrint(Enum $enum) 
    {
        return (string)$enum->value();
    }
}

Enum::registerPrinter(new ValuePrinter());

echo (string)$fby;                        // 'fby'
