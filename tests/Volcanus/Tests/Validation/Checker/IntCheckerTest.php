<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Tests\Validation\Checker;

use Volcanus\Validation\Checker\IntChecker;

/**
 * IntCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class IntCheckerTest extends \PHPUnit_Framework_TestCase
{

	protected $checker;

	public function setUp()
	{
		$this->checker = new IntChecker();
	}

	public function testCheckIsOk()
	{
		$this->assertTrue($this->checker->check('123'));
		$this->assertTrue($this->checker->check('+123'));
		$this->assertTrue($this->checker->check('-123'));
		$this->assertTrue($this->checker->check('-32769'));
		$this->assertTrue($this->checker->check('+32768'));
		$this->assertTrue($this->checker->check('-2147483649'));
		$this->assertTrue($this->checker->check('+2147483648'));
		$this->assertTrue($this->checker->check('65536'));
		$this->assertTrue($this->checker->check('4294967296'));
		$this->assertTrue($this->checker->check('+123', array('min'=>   1)));
		$this->assertTrue($this->checker->check('+123', array('min'=> 123)));
		$this->assertTrue($this->checker->check('+123', array('min'=>   1, 'max' => 123)));
		$this->assertTrue($this->checker->check('+123', array('min'=> 123, 'max' => 123)));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\IntException
	 */
	public function testRaiseIntExceptionWhenCheckIsNgByFormat()
	{
		$this->checker->check('+123.45');
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\MinValueException
	 */
	public function testRaiseMinValueExceptionWhenCheckIsNgByMin()
	{
		$this->checker->check('+123', array('min'=> 124));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\MaxValueException
	 */
	public function testRaiseMaxValueExceptionWhenCheckIsNgByMax()
	{
		$this->checker->check('+123', array('max'=> 122));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\InvalidArgumentException
	 */
	public function testRaiseInvalidArgumentExceptionWhenInvalidMinParameterIsSpecified()
	{
		$this->checker->check('+123', array('min'=> 'A'));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\InvalidArgumentException
	 */
	public function testRaiseInvalidArgumentExceptionWhenInvalidMaxParameterIsSpecified()
	{
		$this->checker->check('-1',array('max'=> 'A'));
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
