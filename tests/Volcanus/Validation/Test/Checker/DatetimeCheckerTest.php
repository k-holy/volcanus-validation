<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\DatetimeChecker;
use Volcanus\Validation\Exception\CheckerException\DatetimeException;

/**
 * DatetimeCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class DatetimeCheckerTest extends TestCase
{

    /** @var  DatetimeChecker */
    protected $checker;

    public function setUp(): void
    {
        $this->checker = new DatetimeChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('1-1-1 00:00:00'));
        $this->assertTrue($this->checker->check('32767-12-31 23:59:59')); // limit of checkdate()
    }

    public function testRaiseDatetimeExceptionWhenInvalidFormat()
    {
        $this->expectException(DatetimeException::class);
        $this->expectExceptionCode(DatetimeException::INVALID_FORMAT);
        $this->checker->check('a-8-9 00:00:00');
    }

    public function testRaiseDatetimeExceptionWhenDateOutOfRange()
    {
        $this->expectException(DatetimeException::class);
        $this->expectExceptionCode(DatetimeException::DATE_OUT_OF_RANGE);
        $this->checker->check('0-8-9 00:00:00');
    }

    public function testRaiseDatetimeExceptionWhenHoursOutOfRange()
    {
        $this->expectException(DatetimeException::class);
        $this->expectExceptionCode(DatetimeException::HOURS_OUT_OF_RANGE);
        $this->checker->check('2012-02-07 25:00:00');
    }

    public function testRaiseDatetimeExceptionWhenMinutesOutOfRange()
    {
        $this->expectException(DatetimeException::class);
        $this->expectExceptionCode(DatetimeException::MINUTES_OUT_OF_RANGE);
        $this->checker->check('2012-02-07 00:60:00');
    }

    public function testRaiseDatetimeExceptionWhenSecondsOutOfRange()
    {
        $this->expectException(DatetimeException::class);
        $this->expectExceptionCode(DatetimeException::SECONDS_OUT_OF_RANGE);
        $this->checker->check('2012-02-07 00:00:60');
    }

    public function testInvokeMethod()
    {
        $this->expectException(DatetimeException::class);
        $this->expectExceptionCode(DatetimeException::SECONDS_OUT_OF_RANGE);
        $checker = $this->checker;
        $checker('2011-08-11 23:00:60');
    }

}
