<?php

use PHPUnit\Framework\TestCase;
use SimpleMvc\BaseModel;


class ConcreteModel extends BaseModel
{
    protected string $name = '';
    protected string $password = '';
    protected int $number = 0;
}
class BaseModelTest extends TestCase
{
    public function testGetExistingProperty()
    {
        $model = ConcreteModel::from([]);
        $model->name = 'John Doe';

        $this->assertEquals('John Doe', $model->name);
    }

    public function testGetNonexistentProperty()
    {
        $model = new ConcreteModel();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Attribute nonExistentProperty does not exist in class ConcreteModel");

        $value = $model->nonExistentProperty;
    }

    public function testSetExistingProperty()
    {
        $model = new ConcreteModel();
        $model->name = 'John Doe';
        $model->name = 'Jane Smith';

        $this->assertEquals('Jane Smith', $model->name);
    }

    public function testSetNonexistentProperty()
    {
        $model = new ConcreteModel();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Attribute nonExistentProperty does not exist in class ConcreteModel");

        $model->nonExistentProperty = 'value';
    }

    public function testSetIncompatibleType()
    {
        $model = new ConcreteModel();
        $model->number = 123;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Attribute number expects type integer, string given.");

        $model->number = 'abc';
    }

    public function testIssetProperty()
    {
        $model = new ConcreteModel();
        $model->password = 'password123';

        $this->assertTrue(isset($model->password));
        $this->assertFalse(isset($model->nonExistentProperty));
    }

    public function testFromArray()
    {
        $array = [
            'name' => 'John Doe',
            'password' => 'password123',
            'number' => 123
        ];

        $model = ConcreteModel::from($array);

        $this->assertInstanceOf(ConcreteModel::class, $model);
        $this->assertEquals('John Doe', $model->name);
        $this->assertEquals('password123', $model->password);
        $this->assertEquals(123, $model->number);
    }

    public function testToArray()
    {
        $model = new ConcreteModel();
        $model->name = 'John Doe';
        $model->password = 'password123';
        $model->number = 123;

        $array = ConcreteModel::toArray($model);

        $expectedArray = [
            'name' => 'John Doe',
            'password' => 'password123',
            'number' => 123
        ];

        $this->assertEquals($expectedArray, $array);
    }

    public function testJsonSerialize()
    {
        $model = new ConcreteModel();
        $model->name = 'John Doe';
        $model->password = 'password123';
        $model->number = 123;

        $json = json_encode($model);

        $expectedJson = '{"name":"John Doe","password":"password123","number":123}';

        $this->assertEquals($expectedJson, $json);
    }

}
