<?php

namespace test\Enum;

use Enum\Enum;
use Enum\EnumException;

class EnumTest extends \PHPUnit_Framework_TestCase
{
    public function testGetItems()
    {
        $items = ExampleEnum::getItems();
        $this->assertCount(3, $items);
        $this->assertContainsOnlyInstancesOf(ExampleEnum::class, $items);

        $foo = $items[ExampleEnum::FOO];
        $this->assertEquals('FOO', $foo->getKey());
        $this->assertEquals('foo', $foo->getValue());

        $bar = $items[ExampleEnum::BAR];
        $this->assertEquals('BAR', $bar->getKey());
        $this->assertEquals('bar', $bar->getValue());
    }

    public function testGet()
    {
        $foo = ExampleEnum::get(ExampleEnum::FOO);
        $this->assertInstanceOf(ExampleEnum::class, $foo);
        $this->assertEquals('FOO', $foo->getKey());
        $this->assertEquals('foo', $foo->getValue());

        $bar = ExampleEnum::get(ExampleEnum::BAR);
        $this->assertInstanceOf(ExampleEnum::class, $bar);
        $this->assertEquals('BAR', $bar->getKey());
        $this->assertEquals('bar', $bar->getValue());
    }

    public function testEquals()
    {
        $foo1 = ExampleEnum::get(ExampleEnum::FOO);
        $foo2 = ExampleEnum::get(ExampleEnum::FOO);
        $foo3 = AnotherExampleEnum::get(AnotherExampleEnum::FOO);
        $bar = ExampleEnum::get(ExampleEnum::BAR);

        $this->assertFalse($foo1->equals($bar));
        $this->assertFalse($foo1->equals('blee'));
        $this->assertTrue($foo1->equals($foo2));
        $this->assertTrue($foo1->equals($foo2, true));
        $this->assertTrue($foo1->equals('foo'));
        $this->assertTrue($foo1->equals($foo3));
        $this->assertFalse($foo1->equals($foo3, true));
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

    public function testGetAndGetItemsWithClassName()
    {
        $this->assertEquals(ExampleEnum::getItems(), Enum::getItems(ExampleEnum::class));
        $this->assertEquals(ExampleEnum::get(ExampleEnum::FOO), Enum::get(ExampleEnum::FOO, ExampleEnum::class));
        $this->assertEquals(ExampleEnum::get(ExampleEnum::BAR), Enum::get(ExampleEnum::BAR, ExampleEnum::class));

        $this->expectException(EnumException::class);
        Enum::get(ExampleEnum::FOO, 'some_fake_class');

        $this->expectException(EnumException::class);
        Enum::getItems('some_fake_class');
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
}
