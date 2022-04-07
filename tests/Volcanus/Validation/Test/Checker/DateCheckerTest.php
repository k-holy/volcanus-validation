<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\DateChecker;
use Volcanus\Validation\Exception\CheckerException\DateException;

/**
 * DateCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class DateCheckerTest extends TestCase
{

    /** @var  DateChecker */
    protected $checker;

    public function setUp(): void
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
        $this->assertTrue($this->checker->check('2000-01-01 00:00:00', ['pattern' => DateChecker::PATTERN_YMDHIS]));
        $this->assertTrue($this->checker->check('2000-01-01 00:00', ['pattern' => DateChecker::PATTERN_YMDHI]));
        $this->assertTrue($this->checker->check('2000-01-01 00', ['pattern' => DateChecker::PATTERN_YMDH]));
        $this->assertTrue($this->checker->check('2000-01-01', ['pattern' => DateChecker::PATTERN_YMD]));
    }

    public function testCheckIsOkWhenPatternIsSpecifiedByRegEx()
    {
        $this->assertTrue($this->checker->check('2000/01/01', ['pattern' => '#^(\d{4})/(\d{2})/(\d{2})$#']));
        $this->assertTrue($this->checker->check('2000 01 01', ['pattern' => '#^(\d{4})\s(\d{2})\s(\d{2})$#']));
    }

    public function testCheckIsOkWhenPatternAndOrderIsSpecified()
    {
        $this->assertTrue($this->checker->check('02 08 2001', [
            'pattern' => '#^(\d{2}) (\d{2}) (\d{4})$#',
            'order' => 'dmy',
        ]));
        $this->assertTrue($this->checker->check('02 08 2001 00:00:00', [
            'pattern' => '#^(\d{2}) (\d{2}) (\d{4}) (\d{2}):(\d{2}):(\d{2})$#',
            'order' => 'dmyhis',
        ]));
    }

    public function testRaiseDateExceptionWhenInvalidFormat()
    {
        $this->expectException(DateException::class);
        $this->expectExceptionCode(DateException::INVALID_FORMAT);
        $this->checker->check('a-8-9');
    }

    public function testRaiseDateExceptionWhenDateOutOfRange()
    {
        $this->expectException(DateException::class);
        $this->expectExceptionCode(DateException::DATE_OUT_OF_RANGE);
        $this->checker->check('0-8-9');
    }

    public function testRaiseDateExceptionWhenHoursOutOfRange()
    {
        $this->expectException(DateException::class);
        $this->expectExceptionCode(DateException::HOURS_OUT_OF_RANGE);
        $this->checker->check('2012-02-07 25:00:00', ['pattern' => DateChecker::PATTERN_YMDHIS]);
    }

    public function testRaiseDateExceptionWhenMinutesOutOfRange()
    {
        $this->expectException(DateException::class);
        $this->expectExceptionCode(DateException::MINUTES_OUT_OF_RANGE);
        $this->checker->check('2012-02-07 00:60:00', ['pattern' => DateChecker::PATTERN_YMDHIS]);
    }

    public function testRaiseDateExceptionWhenSecondsOutOfRange()
    {
        $this->expectException(DateException::class);
        $this->expectExceptionCode(DateException::SECONDS_OUT_OF_RANGE);
        $this->checker->check('2012-02-07 00:00:60', ['pattern' => DateChecker::PATTERN_YMDHIS]);
    }

    public function testInvokeMethod()
    {
        $this->expectException(DateException::class);
        $this->expectExceptionCode(DateException::SECONDS_OUT_OF_RANGE);
        $checker = $this->checker;
        $checker->setOption('pattern', DateChecker::PATTERN_YMDHIS);
        $checker('2012-02-07 00:00:60');
    }

}
