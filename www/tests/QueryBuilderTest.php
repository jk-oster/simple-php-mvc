<?php

use PHPUnit\Framework\TestCase;
use SimpleMvc\QueryBuilder;

class QueryBuilderTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
    }

    public function testSelect(): void
    {
        $queryBuilder = new QueryBuilder('test_table', $this->pdo);
        $queryBuilder->select(['column1', 'column2']);
        $this->assertEquals('SELECT column1, column2 FROM test_table', $queryBuilder->getQuery());
        $this->assertEquals([], $queryBuilder->getParams());
    }

    // Other test methods...
}