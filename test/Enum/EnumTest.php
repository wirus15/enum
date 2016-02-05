<?php

namespace test\Enum;

class EnumTest extends \PHPUnit_Framework_TestCase
{
    public function testGetItems()
    {
        $items = ExampleEnum::getItems();
        $this->assertCount(3, $items);
        $this->assertContainsOnlyInstancesOf(ExampleEnum::class, $items);

        $foo = $items['FOO'];
        $this->assertEquals('FOO', $foo->getKey());
        $this->assertEquals('foo', $foo->getValue());

        $bar = $items['BAR'];
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
        $this->assertFalse($foo1->equals($foo3), true);
    }
}
