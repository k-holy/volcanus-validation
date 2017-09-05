<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\ArraySizeChecker;

/**
 * ArraySizeCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class ArraySizeCheckerTest extends \PHPUnit\Framework\TestCase
{

    /** @var  \Volcanus\Validation\Checker\ArraySizeChecker */
    protected $checker;

    public function setUp()
    {
        $this->checker = new ArraySizeChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check([1, 2, 3], ['minSize' => 1]));
        $this->assertTrue($this->checker->check([1, 2, 3], ['minSize' => 1, 'maxSize' => 3]));
        $this->assertTrue($this->checker->check([1, 2, 3], ['minSize' => 3, 'maxSize' => 3]));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MinValueException
     */
    public function testRaiseMinValueExceptionWhenCheckIsNgByMinValue()
    {
        $this->checker->check([1, 2, 3], ['minSize' => 4]);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MaxValueException
     */
    public function testRaiseMaxValueExceptionWhenCheckIsNgByMaxValue()
    {
        $this->checker->check([1, 2, 3], ['minSize' => 1, 'maxSize' => 2]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenValueIsNotArrayAndNotTraversable()
    {
        $this->checker->check('123');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenInvalidMinValueParameterIsSpecified()
    {
        $this->checker->check([1, 2, 3], ['minSize' => 'A', 'maxSize' => 4]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenInvalidMaxValueParameterIsSpecified()
    {
        $this->checker->check([1, 2, 3], ['minSize' => 1, 'maxSize' => 'A']);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MinValueException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker->setOption('minSize', 4);
        $checker([1, 2, 3]);
    }

}
