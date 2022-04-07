<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\FloatChecker;
use Volcanus\Validation\Exception\CheckerException\FloatException;
use Volcanus\Validation\Exception\CheckerException\MaxValueException;
use Volcanus\Validation\Exception\CheckerException\MinValueException;

/**
 * FloatCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class FloatCheckerTest extends TestCase
{

    /** @var  FloatChecker */
    protected $checker;

    public function setUp(): void
    {
        $this->checker = new FloatChecker();
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('123'));
        $this->assertTrue($this->checker->check('+123.45'));
        $this->assertTrue($this->checker->check('-123.45'));
        $this->assertTrue($this->checker->check('+123', ['min' => 1]));
        $this->assertTrue($this->checker->check('+123', ['min' => 123]));
        $this->assertTrue($this->checker->check('+123', ['min' => 1, 'max' => 123]));
        $this->assertTrue($this->checker->check('+123', ['min' => 123, 'max' => 123]));
    }

    /**
     * @throws \Exception
     */
    public function testRaiseFloatExceptionWhenCheckIsNgByFormat()
    {
        $this->expectException(FloatException::class);
        $this->checker->check('*123.45');
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinValueExceptionWhenCheckIsNgByMin()
    {
        $this->expectException(MinValueException::class);
        $this->checker->check('+123', ['min' => 124]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxValueExceptionWhenCheckIsNgByMax()
    {
        $this->expectException(MaxValueException::class);
        $this->checker->check('+123', ['max' => 122]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseInvalidArgumentExceptionWhenInvalidMinParameterIsSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check('+123', ['min' => 'A']);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseInvalidArgumentExceptionWhenInvalidMaxParameterIsSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check('-1', ['max' => 'A']);
    }

    public function testInvokeMethod()
    {
        $this->expectException(MinValueException::class);
        $checker = $this->checker;
        $checker->setOption('min', 1);
        $checker('-1');
    }

}
