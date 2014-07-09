<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\MinValueChecker;

/**
 * MinValueCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class MinValueCheckerTest extends \PHPUnit_Framework_TestCase
{

	protected $checker;

	public function setUp()
	{
		$this->checker = new MinValueChecker();
	}

	public function testCheckIsOk()
	{
		$this->assertTrue($this->checker->check('123'        , array('min' => 123)));
		$this->assertTrue($this->checker->check('+123'       , array('min' => -123)));
		$this->assertTrue($this->checker->check('-123'       , array('min' => -123)));
		$this->assertTrue($this->checker->check('-32769'     , array('min' => -32769)));
		$this->assertTrue($this->checker->check('+32768'     , array('min' => 32768)));
		$this->assertTrue($this->checker->check('-2147483649', array('min' => -2147483649)));
		$this->assertTrue($this->checker->check('+2147483648', array('min' => 2147483648)));
		$this->assertTrue($this->checker->check('65536'      , array('min' => 65536)));
		$this->assertTrue($this->checker->check('4294967296' , array('min' => 4294967296)));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\InvalidValueException
	 */
	public function testRaiseInvalidValueExceptionWhenCheckIsNgByFormat()
	{
		$this->checker->check('A', array('min' => 123));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\MinValueException
	 */
	public function testRaiseMinValueExceptionWhenCheckIsNgByMinValue()
	{
		$this->checker->check('123', array('min' => 124));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRaiseInvalidArgumentExceptionWhenInvalidMinValueParameterIsSpecified()
	{
		$this->checker->check('123', array('min' => 'A'));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\MinValueException
	 */
	public function testInvokeMethod()
	{
		$checker = $this->checker;
		$checker->setOption('min', 1);
		$checker('-1');
	}

}
