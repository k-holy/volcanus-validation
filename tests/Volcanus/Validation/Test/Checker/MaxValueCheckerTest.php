<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\MaxValueChecker;

/**
 * MaxValueCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class MaxValueCheckerTest extends \PHPUnit_Framework_TestCase
{

	protected $checker;

	public function setUp()
	{
		$this->checker = new MaxValueChecker();
	}

	public function testCheckIsOk()
	{
		$this->assertTrue($this->checker->check('123'        , array('max' => 123)));
		$this->assertTrue($this->checker->check('+123'       , array('max' => 123)));
		$this->assertTrue($this->checker->check('-123'       , array('max' => 123)));
		$this->assertTrue($this->checker->check('-32769'     , array('max' => -32769)));
		$this->assertTrue($this->checker->check('+32768'     , array('max' => 32768)));
		$this->assertTrue($this->checker->check('-2147483649', array('max' => -2147483649)));
		$this->assertTrue($this->checker->check('+2147483648', array('max' => 2147483648)));
		$this->assertTrue($this->checker->check('65536'      , array('max' => 65536)));
		$this->assertTrue($this->checker->check('4294967296' , array('max' => 4294967296)));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\InvalidValueException
	 */
	public function testRaiseInvalidValueExceptionWhenCheckIsNgByFormat()
	{
		$this->checker->check('A', array('max' => 123));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\MaxValueException
	 */
	public function testRaiseMaxValueExceptionWhenCheckIsNgByMaxValue()
	{
		$this->checker->check('123', array('max' => 122));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRaiseInvalidArgumentExceptionWhenInvalidMaxValueParameterIsSpecified()
	{
		$this->checker->check('123', array('max' => 'A'));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\MaxValueException
	 */
	public function testInvokeMethod()
	{
		$checker = $this->checker;
		$checker->setOption('max', -1);
		$checker('1');
	}

}
