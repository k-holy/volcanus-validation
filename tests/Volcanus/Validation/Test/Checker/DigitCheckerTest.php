<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\DigitChecker;
use Volcanus\Validation\Exception\CheckerException\DigitException;

/**
 * DigitCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class DigitCheckerTest extends TestCase
{

    /** @var  DigitChecker */
    protected $checker;

    public function setUp(): void
    {
        $this->checker = new DigitChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('0123'));
    }

    public function testRaiseDigitExceptionWhenCheckIsNgByFormat()
    {
        $this->expectException(DigitException::class);
        $this->checker->check('+123.45');
    }

    public function testInvokeMethod()
    {
        $this->expectException(DigitException::class);
        $checker = $this->checker;
        $checker('+123.45');
    }

}
