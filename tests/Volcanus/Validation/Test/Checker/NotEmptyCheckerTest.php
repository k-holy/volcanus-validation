<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\NotEmptyChecker;
use Volcanus\Validation\Exception\CheckerException\EmptyException;

/**
 * NotEmptyCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class NotEmptyCheckerTest extends TestCase
{

    /** @var  NotEmptyChecker */
    protected NotEmptyChecker $checker;

    public function setUp(): void
    {
        $this->checker = new NotEmptyChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('1'));
    }

    public function testCheckIsOkWhenValueIsArray()
    {
        $this->assertTrue($this->checker->check([1, 2, 3]));
    }

    public function testCheckIsOkWhenValueIsCountable()
    {
        $counter = new \ArrayIterator([1, 2, 3]);
        $this->assertTrue($this->checker->check($counter));
    }

    public function testRaiseEmptyExceptionWhenValueIsNull()
    {
        $this->expectException(EmptyException::class);
        $this->expectExceptionCode(EmptyException::INVALID_NULL);
        $value = null;
        $this->checker->check($value);
    }

    public function testRaiseEmptyExceptionWhenValueIsEmptyString()
    {
        $this->expectException(EmptyException::class);
        $this->expectExceptionCode(EmptyException::EMPTY_STRING);
        $value = '';
        $this->checker->check($value);
    }

    public function testRaiseEmptyExceptionWhenValueIsEmptyArray()
    {
        $this->expectException(EmptyException::class);
        $this->expectExceptionCode(EmptyException::EMPTY_ARRAY);
        $value = [];
        $this->checker->check($value);
    }

    public function testInvokeMethod()
    {
        $this->expectException(EmptyException::class);
        $this->expectExceptionCode(EmptyException::INVALID_NULL);
        $checker = $this->checker;
        $checker(null);
    }

}