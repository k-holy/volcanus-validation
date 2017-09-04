<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
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
		$error = new \Volcanus\Validation\Error('foo', array(1, 2, 3));
		$this->assertEquals('foo', $error->getType());
		$this->assertEquals('foo', $error->type);
	}

	public function testGetParameters()
	{
		$error = new \Volcanus\Validation\Error('foo', array(1, 2, 3));
		$this->assertEquals($error->getParameters(), array(1, 2, 3));
	}

	public function testHasParameter()
	{
		$error = new \Volcanus\Validation\Error('foo', array(1, 2, 3));
		$this->assertTrue($error->has(array(1)));
		$this->assertTrue($error->has(array(1, 2)));
		$this->assertTrue($error->has(array(1, 2, 3)));
	}

	public function testNotHasParameter()
	{
		$error = new \Volcanus\Validation\Error('foo', array(1, 2, 3));
		$this->assertFalse($error->has(array(2)));
		$this->assertFalse($error->has(array(1, 3)));
		$this->assertFalse($error->has(array(1, 2, 4)));
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
