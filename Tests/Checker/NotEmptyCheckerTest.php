<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Tests\Checker;

use Volcanus\Validation\Checker\NotEmptyChecker;

/**
 * NotEmptyCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class NotEmptyCheckerTest extends \PHPUnit_Framework_TestCase
{

	protected $checker;

	public function setUp()
	{
		$this->checker = new NotEmptyChecker();
	}

	public function testCheckIsOk()
	{
		$this->assertTrue($this->checker->check('1'));
	}

	public function testCheckIsOkWhenValueIsArray()
	{
		$this->assertTrue($this->checker->check(array(1,2,3)));
	}

	public function testCheckIsOkWhenValueIsCountable()
	{
		$counter = new \ArrayIterator(array(1, 2, 3));
		$this->assertTrue($this->checker->check($counter));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\EmptyException
	 * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\EmptyException::INVALID_NULL
	 */
	public function testRaiseEmptyExceptionWhenValueIsNull()
	{
		$value = null;
		$this->checker->check($value);
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\EmptyException
	 * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\EmptyException::EMPTY_STRING
	 */
	public function testRaiseEmptyExceptionWhenValueIsEmptyString()
	{
		$value = '';
		$this->checker->check($value);
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\EmptyException
	 * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\EmptyException::EMPTY_ARRAY
	 */
	public function testRaiseEmptyExceptionWhenValueIsEmptyArray()
	{
		$value = array();
		$this->checker->check($value);
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\EmptyException
	 * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\EmptyException::INVALID_NULL
	 */
	public function testInvokeMethod()
	{
		$checker = $this->checker;
		$checker(null);
	}

}