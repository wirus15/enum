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
}
