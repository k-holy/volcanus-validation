<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\CompareChecker;

/**
 * CompareCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class CompareCheckerTest extends \PHPUnit_Framework_TestCase
{

    /** @var  \Volcanus\Validation\Checker\CompareChecker */
    protected $checker;

    public function setUp()
    {
        $this->checker = new CompareChecker();
    }

    public function testCheckIsOk()
    {
        $this->checker->setOption('operator', CompareChecker::IS_EQUAL_TO);
        $this->assertTrue($this->checker->check('1', array('compareTo' => '1')));
        $this->assertTrue($this->checker->check(1, array('compareTo' => 1)));
        $this->assertTrue($this->checker->check('0', array('compareTo' => 0)));
    }

    public function testCheckIsNotEqualIsOk()
    {
        $this->checker->setOption('operator', CompareChecker::IS_NOT_EQUAL);
        $this->assertTrue($this->checker->check('1', array('compareTo' => '0')));
        $this->assertTrue($this->checker->check(1, array('compareTo' => 0)));
        $this->assertTrue($this->checker->check('0', array('compareTo' => 1)));
    }

    public function testCheckIsGreaterThanIsOk()
    {
        $this->checker->setOption('operator', CompareChecker::IS_GREATER_THAN);
        $this->assertTrue($this->checker->check(2, array('compareTo' => 1)));
        $this->assertTrue($this->checker->check('B', array('compareTo' => 'A')));
    }

    public function testCheckIsLessThanIsOk()
    {
        $this->checker->setOption('operator', CompareChecker::IS_LESS_THAN);
        $this->assertTrue($this->checker->check(1, array('compareTo' => 2)));
        $this->assertTrue($this->checker->check('A', array('compareTo' => 'B')));
    }

    public function testCheckIsGreaterThanOrEqualToIsOk()
    {
        $this->checker->setOption('operator', CompareChecker::IS_GREATER_THAN_OR_EQUAL_TO);
        $this->assertTrue($this->checker->check(1, array('compareTo' => 1)));
        $this->assertTrue($this->checker->check(2, array('compareTo' => 1)));
        $this->assertTrue($this->checker->check('A', array('compareTo' => 'A')));
        $this->assertTrue($this->checker->check('B', array('compareTo' => 'A')));
    }

    public function testCheckIsLessThanOrEqualToIsOk()
    {
        $this->checker->setOption('operator', CompareChecker::IS_LESS_THAN_OR_EQUAL_TO);
        $this->assertTrue($this->checker->check(1, array('compareTo' => 1)));
        $this->assertTrue($this->checker->check(1, array('compareTo' => 2)));
        $this->assertTrue($this->checker->check('A', array('compareTo' => 'A')));
        $this->assertTrue($this->checker->check('A', array('compareTo' => 'B')));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\CompareException
     * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\CompareException::INVALID_EQUAL_TO
     */
    public function testRaiseCheckerExceptionWithCodeInvalidEqualToWhenCheckIsNg()
    {
        $this->checker->check(1, array('operator' => CompareChecker::IS_EQUAL_TO, 'compareTo' => 2));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\CompareException
     * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\CompareException::INVALID_NOT_EQUAL
     */
    public function testRaiseCheckerExceptionWithCodeInvalidNotEqualWhenCheckIsNg()
    {
        $this->checker->check(1, array('operator' => CompareChecker::IS_NOT_EQUAL, 'compareTo' => 1));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\CompareException
     * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\CompareException::INVALID_GREATER_THAN
     */
    public function testRaiseCheckerExceptionWithCodeInvalidGreaterThanWhenCheckIsNg()
    {
        $this->checker->check(1, array('operator' => CompareChecker::IS_GREATER_THAN, 'compareTo' => 2));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\CompareException
     * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\CompareException::INVALID_LESS_THAN
     */
    public function testRaiseCheckerExceptionWithCodeInvalidLessThanWhenCheckIsNg()
    {
        $this->checker->check(2, array('operator' => CompareChecker::IS_LESS_THAN, 'compareTo' => 1));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\CompareException
     * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\CompareException::INVALID_GREATER_THAN_OR_EQUAL_TO
     */
    public function testRaiseCheckerExceptionWithCodeInvalidGreaterThanOrEqualToWhenCheckIsNg()
    {
        $this->checker->check(1, array('operator' => CompareChecker::IS_GREATER_THAN_OR_EQUAL_TO, 'compareTo' => 2));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\CompareException
     * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\CompareException::INVALID_LESS_THAN_OR_EQUAL_TO
     */
    public function testRaiseCheckerExceptionWithCodeInvalidLessThanOrEqualToWhenCheckIsNg()
    {
        $this->checker->check(2, array('operator' => CompareChecker::IS_LESS_THAN_OR_EQUAL_TO, 'compareTo' => 1));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenOperatorParameterIsNotSpecified()
    {
        $this->checker->check('1', array('operator' => null, 'compareTo' => '2'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenCompareValueParameterIsNotSpecified()
    {
        $this->checker->check('1', array('operator' => CompareChecker::IS_EQUAL_TO));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenInvalidOperatorParameterIsSpecified()
    {
        $this->checker->check('1', array('operator' => 'foo', 'compareTo' => '2'));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\CompareException
     * @expectedExceptionCode \Volcanus\Validation\Exception\CheckerException\CompareException::INVALID_EQUAL_TO
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker->setOption('operator', CompareChecker::IS_EQUAL_TO);
        $checker->setOption('compareTo', '2');
        $checker('1');
    }

}
