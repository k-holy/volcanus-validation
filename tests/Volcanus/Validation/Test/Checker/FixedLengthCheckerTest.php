<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\FixedLengthChecker;

/**
 * FixedLengthCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class FixedLengthCheckerTest extends \PHPUnit\Framework\TestCase
{

    /** @var  \Volcanus\Validation\Checker\FixedLengthChecker */
	protected $checker;

	public function setUp()
	{
		$this->checker = new FixedLengthChecker();
	}

	public function testCheckIsOk()
	{
		$this->assertTrue($this->checker->check('1'  , array('length' => 1)));
		$this->assertTrue($this->checker->check('123', array('length' => 3)));
	}

	/**
	 * @expectedException \Volcanus\Validation\Exception\CheckerException\MaxLengthException
	 */
	public function testRaiseMaxLengthExceptionWhenLengthOfTheValueIsLongerThanMaxLength()
	{
		$this->checker->check('123', array('length' => 2));
	}

	/**
	 * @expectedException \Volcanus\Validation\Exception\CheckerException\MinLengthException
	 */
	public function testRaiseMinLengthExceptionWhenLengthOfTheValueIsShorterThanMinLength()
	{
		$this->checker->check('123', array('length' => 4));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRaiseInvalidArgumentExceptionWhenLengthParameterIsNotSpecified()
	{
		$this->checker->check('123');
	}

	/**
	 * @expectedException \Volcanus\Validation\Exception\CheckerException\MaxLengthException
	 */
	public function testInvokeMethod()
	{
		$checker = $this->checker;
		$checker->setOption('length', 2);
		$checker('123');
	}

}
