<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\MaxValueChecker;

/**
 * MaxValueCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class MaxValueCheckerTest extends \PHPUnit\Framework\TestCase
{

    /** @var  \Volcanus\Validation\Checker\MaxValueChecker */
    protected $checker;

    public function setUp()
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

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\InvalidValueException
     */
    public function testRaiseInvalidValueExceptionWhenCheckIsNgByFormat()
    {
        $this->checker->check('A', ['max' => 123]);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MaxValueException
     */
    public function testRaiseMaxValueExceptionWhenCheckIsNgByMaxValue()
    {
        $this->checker->check('123', ['max' => 122]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenInvalidMaxValueParameterIsSpecified()
    {
        $this->checker->check('123', ['max' => 'A']);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MaxValueException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker->setOption('max', -1);
        $checker('1');
    }

}
