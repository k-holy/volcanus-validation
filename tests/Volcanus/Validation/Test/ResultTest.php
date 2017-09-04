<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test;

/**
 * ResultTest
 *
 * @author     k.holy74@gmail.com
 */
class ResultTest extends \PHPUnit\Framework\TestCase
{

    /** @var \Volcanus\Validation\Result */
    protected $result;

    public function setUp()
    {
        $this->result = new \Volcanus\Validation\Result();
    }

    public function testInitialize()
    {
        $this->result->init();
        $this->assertEmpty($this->result->getValues());
        $this->assertEmpty($this->result->getErrors());
    }

    public function testSetAndGetValuesByArray()
    {
        $values = [
            'id' => 1,
            'name' => 'foo',
        ];
        $this->assertEquals($values, $this->result->setValues($values)->getValues());
    }

    public function testSetAndGetValuesByArrayIterator()
    {
        $values = [
            'id' => 1,
            'name' => 'foo',
        ];
        $this->assertEquals($values, $this->result->setValues(
            new \ArrayIterator($values))->getValues());
    }

    public function testSetAndGetValue()
    {
        $this->result->setValue('id', 1);
        $this->assertEquals(1, $this->result->getValue('id'));
        $this->result->setValue('name', 'foo');
        $this->assertEquals('foo', $this->result->getValue('name'));
    }

    public function testSetErrorAndHasError()
    {
        $this->result->setError('fieldName', 'errorType', [1, 2, 3]);
        $this->assertTrue($this->result->hasError('fieldName'));
        $this->assertTrue($this->result->hasError('fieldName', 'errorType'));
        $this->assertTrue($this->result->hasError('fieldName', 'errorType', [1]));
        $this->assertTrue($this->result->hasError('fieldName', 'errorType', [1, 2]));
        $this->assertTrue($this->result->hasError('fieldName', 'errorType', [1, 2, 3]));
    }

    public function testSetErrorAndClearErrors()
    {
        $this->result->setError('fieldName1', 'errorType', [1, 2, 3]);
        $this->assertTrue($this->result->hasError('fieldName1'));
        $this->result->setError('fieldName2', 'errorType', [1, 2, 3]);
        $this->assertTrue($this->result->hasError('fieldName2'));
        $this->result->clearErrors();
        $this->assertFalse($this->result->hasError('fieldName1'));
        $this->assertFalse($this->result->hasError('fieldName2'));
    }

    public function testTraversable()
    {
        $values = [
            'id' => 1,
            'name' => 'foo',
        ];
        $this->result->setValues($values);
        foreach ($this->result as $field => $value) {
            $this->assertEquals($values[$field], $value);
        }
    }

    public function testArrayAccessable()
    {
        $values = [
            'id' => 1,
            'name' => 'foo',
        ];
        $this->result->setValues($values);
        $this->assertEquals($this->result['id'], $values['id']);
        $this->assertEquals($this->result['name'], $values['name']);
    }

    public function testCountable()
    {
        $values = [
            'id' => 1,
            'name' => 'foo',
        ];
        $this->result->setValues($values);
        $this->assertEquals(count($values), count($this->result));
    }

}
