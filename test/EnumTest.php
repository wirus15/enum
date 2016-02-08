<?php

namespace test\Enum;

use Enum\Enum;
use Enum\EnumException;

class EnumTest extends \PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        $items = ExampleEnum::all();
        $this->assertCount(3, $items);
        $this->assertContainsOnlyInstancesOf(ExampleEnum::class, $items);

        $foo = $items[ExampleEnum::FOO];
        $this->assertEquals('FOO', $foo->key());
        $this->assertEquals('foo', $foo->value());

        $bar = $items[ExampleEnum::BAR];
        $this->assertEquals('BAR', $bar->key());
        $this->assertEquals('bar', $bar->value());
    }

    public function testGet()
    {
        $foo = ExampleEnum::get(ExampleEnum::FOO);
        $this->assertInstanceOf(ExampleEnum::class, $foo);
        $this->assertEquals('FOO', $foo->key());
        $this->assertEquals('foo', $foo->value());

        $bar = ExampleEnum::get(ExampleEnum::BAR);
        $this->assertInstanceOf(ExampleEnum::class, $bar);
        $this->assertEquals('BAR', $bar->key());
        $this->assertEquals('bar', $bar->value());
    }

    public function testIs()
    {
        $foo1 = ExampleEnum::get(ExampleEnum::FOO);
        $foo2 = ExampleEnum::get(ExampleEnum::FOO);
        $foo3 = AnotherExampleEnum::get(AnotherExampleEnum::FOO);
        $bar = ExampleEnum::get(ExampleEnum::BAR);

        $this->assertFalse($foo1->is($bar));
        $this->assertFalse($foo1->is('blee'));
        $this->assertTrue($foo1->is($foo2));
        $this->assertTrue($foo1->is($foo2, true));
        $this->assertTrue($foo1->is('foo'));
        $this->assertTrue($foo1->is($foo3));
        $this->assertFalse($foo1->is($foo3, true));
    }

    public function testIn()
    {
        $foo = ExampleEnum::get(ExampleEnum::FOO);
        $bar = ExampleEnum::get(ExampleEnum::BAR);
        $xyz = ExampleEnum::get(ExampleEnum::XYZ);

        $this->assertTrue($foo->in(['foo', 'xyz']));
        $this->assertTrue($foo->in([$foo, $bar]));
        $this->assertFalse($foo->in(['bar', 'xyz']));
        $this->assertFalse($foo->in([$bar, $xyz]));
        $this->assertTrue($bar->in([$bar, 'bar']));
        $this->assertFalse($xyz->in([]));
    }

    public function testToString()
    {
        $foo = ExampleEnum::get(ExampleEnum::FOO);
        $bar = ExampleEnum::get(ExampleEnum::BAR);

        $this->assertEquals('Foo', (string)$foo);
        $this->assertEquals('Bar', (string)$bar);
    }

    public function testRegisterPrinter()
    {
        $foo = ExampleEnum::get(ExampleEnum::FOO);
        $bar = ExampleEnum::get(ExampleEnum::BAR);
        $printer = new UppercasePrinter();
        Enum::registerPrinter($printer);

        $this->assertEquals('FOO', (string)$foo);
        $this->assertEquals('BAR', (string)$bar);

        // revert do default printer
        Enum::registerPrinter(null);
        $this->assertEquals('Foo', (string)$foo);
        $this->assertEquals('Bar', (string)$bar);
    }

    public function testGetAndAllWithClassName()
    {
        $this->assertEquals(ExampleEnum::all(), Enum::all(ExampleEnum::class));
        $this->assertEquals(ExampleEnum::get(ExampleEnum::FOO), Enum::get(ExampleEnum::FOO, ExampleEnum::class));
        $this->assertEquals(ExampleEnum::get(ExampleEnum::BAR), Enum::get(ExampleEnum::BAR, ExampleEnum::class));

        $this->expectException(EnumException::class);
        Enum::get(ExampleEnum::FOO, 'some_fake_class');

        $this->expectException(EnumException::class);
        Enum::all('some_fake_class');
    }

    public function testCallStatic()
    {
        $this->assertEquals(ExampleEnum::get(ExampleEnum::FOO), ExampleEnum::FOO());
        $this->assertEquals(ExampleEnum::get(ExampleEnum::BAR), ExampleEnum::BAR());

        $this->expectException(EnumException::class);
        ExampleEnum::WRONG();
    }

    public function testKeys()
    {
        $expected = ['FOO', 'BAR', 'XYZ'];
        $this->assertEquals($expected, ExampleEnum::keys());
        $this->assertEquals($expected, Enum::keys(ExampleEnum::class));
    }

    public function testValues()
    {
        $expected = ['foo', 'bar', 'xyz'];
        $this->assertEquals($expected, ExampleEnum::values());
        $this->assertEquals($expected, Enum::values(ExampleEnum::class));
    }

    public function testClone()
    {
        $foo = ExampleEnum::FOO();

        $this->expectException(EnumException::class);
        $clone = clone $foo;
    }

    public function testHas()
    {
        $this->assertTrue(ExampleEnum::has('foo'));
        $this->assertTrue(ExampleEnum::has('bar'));
        $this->assertTrue(Enum::has('xyz', ExampleEnum::class));
        $this->assertFalse(ExampleEnum::has('wrong'));
    }
}
