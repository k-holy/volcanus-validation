<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\MinValueChecker;

/**
 * MinValueCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class MinValueCheckerTest extends \PHPUnit\Framework\TestCase
{

    /** @var  \Volcanus\Validation\Checker\MinValueChecker */
    protected $checker;

    public function setUp()
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

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\InvalidValueException
     */
    public function testRaiseInvalidValueExceptionWhenCheckIsNgByFormat()
    {
        $this->checker->check('A', ['min' => 123]);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MinValueException
     */
    public function testRaiseMinValueExceptionWhenCheckIsNgByMinValue()
    {
        $this->checker->check('123', ['min' => 124]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenInvalidMinValueParameterIsSpecified()
    {
        $this->checker->check('123', ['min' => 'A']);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MinValueException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker->setOption('min', 1);
        $checker('-1');
    }

}
