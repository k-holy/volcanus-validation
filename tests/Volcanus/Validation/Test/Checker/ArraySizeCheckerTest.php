<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\ArraySizeChecker;
use Volcanus\Validation\Exception\CheckerException\MaxValueException;
use Volcanus\Validation\Exception\CheckerException\MinValueException;

/**
 * ArraySizeCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class ArraySizeCheckerTest extends TestCase
{

    /** @var  ArraySizeChecker */
    protected $checker;

    public function setUp(): void
    {
        $this->checker = new ArraySizeChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check([1, 2, 3], ['minSize' => 1]));
        $this->assertTrue($this->checker->check([1, 2, 3], ['minSize' => 1, 'maxSize' => 3]));
        $this->assertTrue($this->checker->check([1, 2, 3], ['minSize' => 3, 'maxSize' => 3]));
    }

    public function testRaiseMinValueExceptionWhenCheckIsNgByMinValue()
    {
        $this->expectException(MinValueException::class);
        $this->checker->check([1, 2, 3], ['minSize' => 4]);
    }

    public function testRaiseMaxValueExceptionWhenCheckIsNgByMaxValue()
    {
        $this->expectException(MaxValueException::class);
        $this->checker->check([1, 2, 3], ['minSize' => 1, 'maxSize' => 2]);
    }

    public function testRaiseInvalidArgumentExceptionWhenValueIsNotArrayAndNotTraversable()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check('123');
    }

    public function testRaiseInvalidArgumentExceptionWhenInvalidMinValueParameterIsSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check([1, 2, 3], ['minSize' => 'A', 'maxSize' => 4]);
    }

    public function testRaiseInvalidArgumentExceptionWhenInvalidMaxValueParameterIsSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check([1, 2, 3], ['minSize' => 1, 'maxSize' => 'A']);
    }

    public function testInvokeMethod()
    {
        $this->expectException(MinValueException::class);
        $checker = $this->checker;
        $checker->setOption('minSize', 4);
        $checker([1, 2, 3]);
    }

}
