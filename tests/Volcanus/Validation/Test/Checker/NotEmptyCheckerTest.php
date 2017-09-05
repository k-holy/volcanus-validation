<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\NotEmptyChecker;

/**
 * NotEmptyCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class NotEmptyCheckerTest extends \PHPUnit\Framework\TestCase
{

    /** @var  \Volcanus\Validation\Checker\NotEmptyChecker */
    protected $checker;

    public function setUp()
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

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\EmptyException
     * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\EmptyException::INVALID_NULL
     */
    public function testRaiseEmptyExceptionWhenValueIsNull()
    {
        $value = null;
        $this->checker->check($value);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\EmptyException
     * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\EmptyException::EMPTY_STRING
     */
    public function testRaiseEmptyExceptionWhenValueIsEmptyString()
    {
        $value = '';
        $this->checker->check($value);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\EmptyException
     * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\EmptyException::EMPTY_ARRAY
     */
    public function testRaiseEmptyExceptionWhenValueIsEmptyArray()
    {
        $value = [];
        $this->checker->check($value);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\EmptyException
     * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\EmptyException::INVALID_NULL
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker(null);
    }

}