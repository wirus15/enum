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
        $this->assertEquals($foo, ExampleEnum::get($foo));

        $bar = ExampleEnum::get(ExampleEnum::BAR);
        $this->assertInstanceOf(ExampleEnum::class, $bar);
        $this->assertEquals('BAR', $bar->key());
        $this->assertEquals('bar', $bar->value());
        $this->assertEquals($bar, ExampleEnum::get($bar));
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

    public function testLabelAndToString()
    {
        $foo = ExampleEnum::get(ExampleEnum::FOO);
        $bar = ExampleEnum::get(ExampleEnum::BAR);

        $this->assertEquals('Foo', $foo->label());
        $this->assertEquals('Bar', $bar->label());
        $this->assertEquals($foo->label(), (string)$foo);
        $this->assertEquals($bar->label(), (string)$bar);
    }

    public function testSetLabelProvider()
    {
        $foo = ExampleEnum::get(ExampleEnum::FOO);
        $bar = ExampleEnum::get(ExampleEnum::BAR);
        $labelProvider = new UppercaseLabelProvider();
        Enum::setLabelProvider($labelProvider);

        $this->assertEquals('FOO', $foo->label());
        $this->assertEquals('BAR', $bar->label());

        // revert do default label provider
        Enum::setLabelProvider(null);
        $this->assertEquals('Foo', $foo->label());
        $this->assertEquals('Bar', $bar->label());
    }

    public function testGetAndAllWithClassName()
    {
        $this->assertEquals(ExampleEnum::all(), Enum::all(ExampleEnum::class));
        $foo = ExampleEnum::get(ExampleEnum::FOO);
        $bar = ExampleEnum::get(ExampleEnum::BAR);
        $this->assertEquals($foo, Enum::get(ExampleEnum::FOO, ExampleEnum::class));
        $this->assertEquals($bar, Enum::get(ExampleEnum::BAR, ExampleEnum::class));
        $this->assertEquals($foo, Enum::get($foo, ExampleEnum::class));
        $this->assertEquals($bar, Enum::get($bar, ExampleEnum::class));

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
