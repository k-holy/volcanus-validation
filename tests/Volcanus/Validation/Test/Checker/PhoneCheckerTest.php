<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\PhoneChecker;
use Volcanus\Validation\Exception\CheckerException\PhoneException;

/**
 * PhoneCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class PhoneCheckerTest extends TestCase
{

    /** @var  PhoneChecker */
    protected PhoneChecker $checker;

    public function setUp(): void
    {
        $this->checker = new PhoneChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('+81-6-584-2222'));
    }

    public function testCheckIsOkWithJpLocale()
    {
        $this->assertTrue($this->checker->check('06-584-2222', ['locale' => 'jp']));
    }

    public function testRaisePhoneExceptionWhenCheckIsNgByFormat()
    {
        $this->expectException(PhoneException::class);
        $this->checker->check('-5');
    }

    public function testInvokeMethod()
    {
        $this->expectException(PhoneException::class);
        $checker = $this->checker;
        $checker->setOption('locale', 'jp');
        $checker('+81-6-584-2222');
    }

}
