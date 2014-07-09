<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\DatetimeChecker;

/**
 * DatetimeCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class DatetimeCheckerTest extends \PHPUnit_Framework_TestCase
{

	protected $checker;

	public function setUp()
	{
		$this->checker = new DatetimeChecker();
	}

	public function testCheckIsOk()
	{
		$this->assertTrue($this->checker->check('1-1-1 00:00:00'));
		$this->assertTrue($this->checker->check('32767-12-31 23:59:59')); // limit of checkdate()
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\DatetimeException
	 * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\DatetimeException::INVALID_FORMAT
	 */
	public function testRaiseDatetimeExceptionWhenInvalidFormat()
	{
		$this->checker->check('a-8-9 00:00:00');
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\DatetimeException
	 * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\DatetimeException::DATE_OUT_OF_RANGE
	 */
	public function testRaiseDatetimeExceptionWhenDateOutOfRange()
	{
		$this->checker->check('0-8-9 00:00:00');
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\DatetimeException
	 * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\DatetimeException::HOURS_OUT_OF_RANGE
	 */
	public function testRaiseDatetimeExceptionWhenHoursOutOfRange()
	{
		$this->checker->check('2012-02-07 25:00:00');
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\DatetimeException
	 * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\DatetimeException::MINUTES_OUT_OF_RANGE
	 */
	public function testRaiseDatetimeExceptionWhenMinutesOutOfRange()
	{
		$this->checker->check('2012-02-07 00:60:00');
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\DatetimeException
	 * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\DatetimeException::SECONDS_OUT_OF_RANGE
	 */
	public function testRaiseDatetimeExceptionWhenSecondsOutOfRange()
	{
		$this->checker->check('2012-02-07 00:00:60');
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\DatetimeException
	 * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\DatetimeException::SECONDS_OUT_OF_RANGE
	 */
	public function testInvokeMethod()
	{
		$checker = $this->checker;
		$checker('2011-08-11 23:00:60');
	}

}
