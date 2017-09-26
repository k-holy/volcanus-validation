<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\ArraySizeChecker;

/**
 * ArraySizeCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class ArraySizeCheckerTest extends \PHPUnit_Framework_TestCase
{

    protected $checker;

    public function setUp()
    {
        $this->checker = new ArraySizeChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check(array(1, 2, 3), array('minSize' => 1)));
        $this->assertTrue($this->checker->check(array(1, 2, 3), array('minSize' => 1, 'maxSize' => 3)));
        $this->assertTrue($this->checker->check(array(1, 2, 3), array('minSize' => 3, 'maxSize' => 3)));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MinValueException
     */
    public function testRaiseMinValueExceptionWhenCheckIsNgByMinValue()
    {
        $this->checker->check(array(1, 2, 3), array('minSize' => 4));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MaxValueException
     */
    public function testRaiseMaxValueExceptionWhenCheckIsNgByMaxValue()
    {
        $this->checker->check(array(1, 2, 3), array('minSize' => 1, 'maxSize' => 2));
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
        $this->checker->check(array(1, 2, 3), array('minSize' => 'A', 'maxSize' => 4));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenInvalidMaxValueParameterIsSpecified()
    {
        $this->checker->check(array(1, 2, 3), array('minSize' => 1, 'maxSize' => 'A'));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MinValueException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker->setOption('minSize', 4);
        $checker(array(1, 2, 3));
    }

}
