<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */

namespace Volcanus\Validation\Test;

use Volcanus\Validation\Result;

/**
 * ResultTest
 *
 * @author     k.holy74@gmail.com
 */
class ResultTest extends \PHPUnit_Framework_TestCase
{

    protected $result;

    public function setUp()
    {
        $this->result = new Result();
    }

    public function testInitialize()
    {
        $this->result->init();
        $this->assertEmpty($this->result->getValues());
        $this->assertEmpty($this->result->getErrors());
    }

    public function testSetAndGetValuesByArray()
    {
        $values = array(
            'id' => 1,
            'name' => 'foo',
        );
        $this->assertEquals($values, $this->result->setValues($values)->getValues());
    }

    public function testSetAndGetValuesByArrayIterator()
    {
        $values = array(
            'id' => 1,
            'name' => 'foo',
        );
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
        $this->result->setError('fieldName', 'errorType', array(1, 2, 3));
        $this->assertTrue($this->result->hasError('fieldName'));
        $this->assertTrue($this->result->hasError('fieldName', 'errorType'));
        $this->assertTrue($this->result->hasError('fieldName', 'errorType', array(1)));
        $this->assertTrue($this->result->hasError('fieldName', 'errorType', array(1, 2)));
        $this->assertTrue($this->result->hasError('fieldName', 'errorType', array(1, 2, 3)));
    }

    public function testTraversable()
    {
        $values = array(
            'id' => 1,
            'name' => 'foo',
        );
        $this->result->setValues($values);
        foreach ($this->result as $field => $value) {
            $this->assertEquals($values[$field], $value);
        }
    }

    public function testArrayAccessable()
    {
        $values = array(
            'id' => 1,
            'name' => 'foo',
        );
        $this->result->setValues($values);
        $this->assertEquals($this->result['id'], $values['id']);
        $this->assertEquals($this->result['name'], $values['name']);
    }

    public function testCountable()
    {
        $values = array(
            'id' => 1,
            'name' => 'foo',
        );
        $this->result->setValues($values);
        $this->assertEquals(count($values), count($this->result));
    }

}
