<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\DateChecker;

/**
 * DateCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class DateCheckerTest extends \PHPUnit\Framework\TestCase
{

    /** @var  \Volcanus\Validation\Checker\DateChecker */
	protected $checker;

	public function setUp()
	{
		$this->checker = new DateChecker();
	}

	public function testCheckIsOk()
	{
		$this->assertTrue($this->checker->check('1-1-1'));
		$this->assertTrue($this->checker->check('32767-1-1')); // limit of checkdate()
	}

	public function testCheckIsOkWhenPatternIsSpecified()
	{
		$this->assertTrue($this->checker->check('2000-01-01 00:00:00', array('pattern' => DateChecker::PATTERN_YMDHIS)));
		$this->assertTrue($this->checker->check('2000-01-01 00:00'   , array('pattern' => DateChecker::PATTERN_YMDHI)));
		$this->assertTrue($this->checker->check('2000-01-01 00'      , array('pattern' => DateChecker::PATTERN_YMDH)));
		$this->assertTrue($this->checker->check('2000-01-01'         , array('pattern' => DateChecker::PATTERN_YMD)));
	}

	public function testCheckIsOkWhenPatternIsSpecifiedByRegEx()
	{
		$this->assertTrue($this->checker->check('2000/01/01', array('pattern' => '#^(\d{4})/(\d{2})/(\d{2})$#')));
		$this->assertTrue($this->checker->check('2000 01 01', array('pattern' => '#^(\d{4})\s(\d{2})\s(\d{2})$#')));
	}

	public function testCheckIsOkWhenPatternAndOrderIsSpecified()
	{
		$this->assertTrue($this->checker->check('02 08 2001', array(
			'pattern' => '#^(\d{2}) (\d{2}) (\d{4})$#',
			'order'   => 'dmy',
		)));
		$this->assertTrue($this->checker->check('02 08 2001 00:00:00', array(
			'pattern' => '#^(\d{2}) (\d{2}) (\d{4}) (\d{2}):(\d{2}):(\d{2})$#',
			'order'   => 'dmyhis',
		)));
	}

	/**
	 * @expectedException \Volcanus\Validation\Exception\CheckerException\DateException
	 * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\DateException::INVALID_FORMAT
	 */
	public function testRaiseDateExceptionWhenInvalidFormat()
	{
		$this->checker->check('a-8-9');
	}

	/**
	 * @expectedException \Volcanus\Validation\Exception\CheckerException\DateException
	 * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\DateException::DATE_OUT_OF_RANGE
	 */
	public function testRaiseDateExceptionWhenDateOutOfRange()
	{
		$this->checker->check('0-8-9');
	}

	/**
	 * @expectedException \Volcanus\Validation\Exception\CheckerException\DateException
	 * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\DateException::HOURS_OUT_OF_RANGE
	 */
	public function testRaiseDateExceptionWhenHoursOutOfRange()
	{
		$this->checker->check('2012-02-07 25:00:00', array('pattern' => DateChecker::PATTERN_YMDHIS));
	}

	/**
	 * @expectedException \Volcanus\Validation\Exception\CheckerException\DateException
	 * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\DateException::MINUTES_OUT_OF_RANGE
	 */
	public function testRaiseDateExceptionWhenMinutesOutOfRange()
	{
		$this->checker->check('2012-02-07 00:60:00', array('pattern' => DateChecker::PATTERN_YMDHIS));
	}

	/**
	 * @expectedException \Volcanus\Validation\Exception\CheckerException\DateException
	 * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\DateException::SECONDS_OUT_OF_RANGE
	 */
	public function testRaiseDateExceptionWhenSecondsOutOfRange()
	{
		$this->checker->check('2012-02-07 00:00:60', array('pattern' => DateChecker::PATTERN_YMDHIS));
	}

	/**
	 * @expectedException \Volcanus\Validation\Exception\CheckerException\DateException
	 * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\DateException::SECONDS_OUT_OF_RANGE
	 */
	public function testInvokeMethod()
	{
		$checker = $this->checker;
		$checker->setOption('pattern', DateChecker::PATTERN_YMDHIS);
		$checker('2012-02-07 00:00:60');
	}

}
