<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\FixedLengthChecker;

/**
 * FixedLengthCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class FixedLengthCheckerTest extends \PHPUnit\Framework\TestCase
{

    /** @var  \Volcanus\Validation\Checker\FixedLengthChecker */
    protected $checker;

    public function setUp()
    {
        $this->checker = new FixedLengthChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('1', ['length' => 1]));
        $this->assertTrue($this->checker->check('123', ['length' => 3]));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MaxLengthException
     */
    public function testRaiseMaxLengthExceptionWhenLengthOfTheValueIsLongerThanMaxLength()
    {
        $this->checker->check('123', ['length' => 2]);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenLengthOfTheValueIsShorterThanMinLength()
    {
        $this->checker->check('123', ['length' => 4]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenLengthParameterIsNotSpecified()
    {
        $this->checker->check('123');
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MaxLengthException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker->setOption('length', 2);
        $checker('123');
    }

}
