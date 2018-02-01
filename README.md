# Enum
A simple PHP library for creating Enum objects.

## Examples
Defining a new Enum class
```php
use Enum\Enum;

// defining our Enum class
final class Example extends Enum
{
    const FOO = 1;
    const BAR = 2;
    const YOLO = 3;
}
```

Basic usage
```php
$foo = Example::get(Example::FOO);
$bar = Example::get('bar');
$yolo = Example::YOLO();

function test(Example $enum) 
{
    if ($enum->is(Example::FOO)) {
        echo 'foo';
    } else if ($enum->is(Example::BAR)) {
        echo 'bar';
    } else {
        echo $enum;
    }
}

test($foo);   // foo
test($bar);   // bar
test($yolo);  // Yolo

```

Getting Enum instances, listing keys and values

```php
Example::get(Example::FOO);               // instance of Example
Example::FOO();                           // shortcut
Example::all();                           // array of Example instances

// keys and values
Example::keys();                          // array of keys ['FOO', 'BAR', 'YOLO']
Example::values();                        // array of values [1, 2, 3]

// checking if value exists
Example::has(3);                          // true
Example::has(4);                          // false
```

Getting single Enum properties
```php
$foo = Example::FOO();
$foo->key();                              // 'FOO'
$foo->value();                            // 1
```

Comparing Enums
```php
$foo = Example::FOO();
$foo->is(1);                              // true
$foo->is(Example::FOO);                   // true
$foo->is($foo);                           // true

// comparing with strict option (type comparison)
$foo->is($foo, true);                     // true
$foo->is(1, true);                        // false

// searching in array
$foo->in([1,2,3]);                        // true
$foo->in([2,3]);                          // false
```
You can also make static calls and specify Enum class

```php
Enum::get(Example::FOO, Example::class);
Enum::all(Example::class);    
Enum::keys(Example::class);      
Enum::values(Example::class); 
Enum::has(1, Example::class);
```

## Development and tests

To develop this lib we are using [docker](http://docker.io) and [docker-compose](https://docs.docker.com/compose/overview/).
After installation of those you should run:

```
bash
docker-compose run enum bash
```

Then on docker console run:

```bash
composer install
composer test
```

## License
This library is released under [MIT license](./LICENSE.md).
