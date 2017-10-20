<?php

namespace test\Enum;

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

    public function testOnly()
    {
        $items = ExampleEnum::only([ExampleEnum::FOO, ExampleEnum::BAR]);
        $this->assertCount(2, $items);
        $this->assertContainsOnlyInstancesOf(ExampleEnum::class, $items);

        $this->assertEquals(ExampleEnum::FOO(), $items[ExampleEnum::FOO]);
        $this->assertEquals(ExampleEnum::BAR(), $items[ExampleEnum::BAR]);
        $this->assertEquals(
            ExampleEnum::only([
                ExampleEnum::FOO(),
                ExampleEnum::BAR(),
            ]),
            ExampleEnum::only([
                ExampleEnum::FOO,
                ExampleEnum::BAR,
            ])
        );
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

        $this->assertEquals($foo, ExampleEnum::get($foo));
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

        $this->assertEquals('foo', (string)$foo);
        $this->assertEquals('bar', (string)$bar);
    }

    public function testCallStatic()
    {
        $this->assertEquals(ExampleEnum::get(ExampleEnum::FOO), ExampleEnum::FOO());
        $this->assertEquals(ExampleEnum::get(ExampleEnum::BAR), ExampleEnum::BAR());
    }

    /**
     * @expectedException \Enum\EnumException
     */
    public function testStaticCallThrowsExceptionWhenMethodDoesntExist()
    {
        ExampleEnum::WRONG();
    }

    /**
     * @expectedException \Enum\EnumException
     */
    public function testGetThrowsExceptionWhenValueDoesntExist()
    {
        ExampleEnum::get('wrong_value');
    }

    public function testKeys()
    {
        $expected = ['FOO', 'BAR', 'XYZ'];
        $this->assertEquals($expected, ExampleEnum::keys());
    }

    public function testValues()
    {
        $expected = ['foo', 'bar', 'xyz'];
        $this->assertEquals($expected, ExampleEnum::values());
    }

    /**
     * @expectedException \Enum\EnumException
     */
    public function testClone()
    {
        $foo = ExampleEnum::FOO();

        $clone = clone $foo;
    }

    public function testHas()
    {
        $this->assertTrue(ExampleEnum::has('foo'));
        $this->assertTrue(ExampleEnum::has('bar'));
        $this->assertFalse(ExampleEnum::has('wrong'));
    }
}
