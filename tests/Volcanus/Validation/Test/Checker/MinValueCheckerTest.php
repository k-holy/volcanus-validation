<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\MinValueChecker;
use Volcanus\Validation\Exception\CheckerException\InvalidValueException;
use Volcanus\Validation\Exception\CheckerException\MinValueException;

/**
 * MinValueCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class MinValueCheckerTest extends TestCase
{

    /** @var  MinValueChecker */
    protected MinValueChecker $checker;

    public function setUp(): void
    {
        $this->checker = new MinValueChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('123', ['min' => 123]));
        $this->assertTrue($this->checker->check('+123', ['min' => -123]));
        $this->assertTrue($this->checker->check('-123', ['min' => -123]));
        $this->assertTrue($this->checker->check('-32769', ['min' => -32769]));
        $this->assertTrue($this->checker->check('+32768', ['min' => 32768]));
        $this->assertTrue($this->checker->check('-2147483649', ['min' => -2147483649]));
        $this->assertTrue($this->checker->check('+2147483648', ['min' => 2147483648]));
        $this->assertTrue($this->checker->check('65536', ['min' => 65536]));
        $this->assertTrue($this->checker->check('4294967296', ['min' => 4294967296]));
    }

    public function testRaiseInvalidValueExceptionWhenCheckIsNgByFormat()
    {
        $this->expectException(InvalidValueException::class);
        $this->checker->check('A', ['min' => 123]);
    }

    public function testRaiseMinValueExceptionWhenCheckIsNgByMinValue()
    {
        $this->expectException(MinValueException::class);
        $this->checker->check('123', ['min' => 124]);
    }

    public function testRaiseInvalidArgumentExceptionWhenInvalidMinValueParameterIsSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check('123', ['min' => 'A']);
    }

    public function testInvokeMethod()
    {
        $this->expectException(MinValueException::class);
        $checker = $this->checker;
        $checker->setOption('min', 1);
        $checker('-1');
    }

}
