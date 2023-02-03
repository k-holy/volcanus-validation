<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\MaxValueChecker;
use Volcanus\Validation\Exception\CheckerException\InvalidValueException;
use Volcanus\Validation\Exception\CheckerException\MaxValueException;

/**
 * MaxValueCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class MaxValueCheckerTest extends TestCase
{

    /** @var  MaxValueChecker */
    protected MaxValueChecker $checker;

    public function setUp(): void
    {
        $this->checker = new MaxValueChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('123', ['max' => 123]));
        $this->assertTrue($this->checker->check('+123', ['max' => 123]));
        $this->assertTrue($this->checker->check('-123', ['max' => 123]));
        $this->assertTrue($this->checker->check('-32769', ['max' => -32769]));
        $this->assertTrue($this->checker->check('+32768', ['max' => 32768]));
        $this->assertTrue($this->checker->check('-2147483649', ['max' => -2147483649]));
        $this->assertTrue($this->checker->check('+2147483648', ['max' => 2147483648]));
        $this->assertTrue($this->checker->check('65536', ['max' => 65536]));
        $this->assertTrue($this->checker->check('4294967296', ['max' => 4294967296]));
    }

    public function testRaiseInvalidValueExceptionWhenCheckIsNgByFormat()
    {
        $this->expectException(InvalidValueException::class);
        $this->checker->check('A', ['max' => 123]);
    }

    public function testRaiseMaxValueExceptionWhenCheckIsNgByMaxValue()
    {
        $this->expectException(MaxValueException::class);
        $this->checker->check('123', ['max' => 122]);
    }

    public function testRaiseInvalidArgumentExceptionWhenInvalidMaxValueParameterIsSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check('123', ['max' => 'A']);
    }

    public function testInvokeMethod()
    {
        $this->expectException(MaxValueException::class);
        $checker = $this->checker;
        $checker->setOption('max', -1);
        $checker('1');
    }

}
