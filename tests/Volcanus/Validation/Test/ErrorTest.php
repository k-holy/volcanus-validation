<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test;

/**
 * ErrorTest
 *
 * @author     k.holy74@gmail.com
 */
class ErrorTest extends \PHPUnit\Framework\TestCase
{

    public function testGetType()
    {
        $error = new \Volcanus\Validation\Error('foo', [1, 2, 3]);
        $this->assertEquals('foo', $error->getType());
        $this->assertEquals('foo', $error->type);
    }

    public function testGetParameters()
    {
        $error = new \Volcanus\Validation\Error('foo', [1, 2, 3]);
        $this->assertEquals($error->getParameters(), [1, 2, 3]);
    }

    public function testHasParameter()
    {
        $error = new \Volcanus\Validation\Error('foo', [1, 2, 3]);
        $this->assertTrue($error->has([1]));
        $this->assertTrue($error->has([1, 2]));
        $this->assertTrue($error->has([1, 2, 3]));
    }

    public function testNotHasParameter()
    {
        $error = new \Volcanus\Validation\Error('foo', [1, 2, 3]);
        $this->assertFalse($error->has([2]));
        $this->assertFalse($error->has([1, 3]));
        $this->assertFalse($error->has([1, 2, 4]));
    }

    public function testSetAndGetMessage()
    {
        $error = new \Volcanus\Validation\Error('foo');
        $this->assertEquals('error!', $error->setMessage('error!')->getMessage());
    }

    public function testToString()
    {
        $error = new \Volcanus\Validation\Error('foo');
        $this->assertEquals('error!', (string)$error->setMessage('error!'));
    }

}
