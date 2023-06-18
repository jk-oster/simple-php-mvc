<?php

use PHPUnit\Framework\TestCase;
use SimpleMvc\Collection;

class CollectionTest extends TestCase
{
    public function testMap()
    {
        $collection = new Collection([1, 2, 3]);
        $result = $collection->map(fn($item) => $item * 2);
        $this->assertEquals([2, 4, 6], $result->toArray());
    }

    public function testFilter()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $result = $collection->filter(fn($item) => $item > 2)->toArray();
        $this->assertEquals([2=>3, 3=>4, 4=>5], $result);
    }

    public function testReduce()
    {
        $collection = new Collection([1, 2, 3]);
        $result = $collection->reduce(fn($carry, $item) => $carry + $item, 0);
        $this->assertEquals(6, $result);
    }

    public function testJoin()
    {
        $collection = new Collection(['a', 'b', 'c']);
        $result = $collection->join(',');
        $this->assertEquals('a,b,c', $result);
    }

    public function testPipe()
    {
        $collection = new Collection([1, 2, 3]);
        $result = $collection->pipe(fn($collection) => $collection->map(fn($item) => $item * 2))->join(',');
        $this->assertEquals('2,4,6', $result);
    }

    public function testCount()
    {
        $collection = new Collection([1, 2, 3]);
        $result = $collection->count();
        $this->assertEquals(3, $result);
    }

    public function testFirst()
    {
        $collection = new Collection([1, 2, 3]);
        $result = $collection->first();
        $this->assertEquals(1, $result);
    }

    public function testLast()
    {
        $collection = new Collection([1, 2, 3]);
        $result = $collection->last();
        $this->assertEquals(3, $result);
    }

    public function testIsEmpty()
    {
        $collection = new Collection([]);
        $result = $collection->isEmpty();
        $this->assertTrue($result);
    }

    public function testIsNotEmpty()
    {
        $collection = new Collection([1, 2, 3]);
        $result = $collection->isNotEmpty();
        $this->assertTrue($result);
    }

    public function testToArray()
    {
        $collection = new Collection([1, 2, 3]);
        $result = $collection->toArray();
        $this->assertEquals([1, 2, 3], $result);
    }
}
