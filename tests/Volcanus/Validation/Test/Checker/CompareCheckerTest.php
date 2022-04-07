<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\CompareChecker;
use Volcanus\Validation\Exception\CheckerException\CompareException;

/**
 * CompareCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class CompareCheckerTest extends TestCase
{

    /** @var  CompareChecker */
    protected $checker;

    public function setUp(): void
    {
        $this->checker = new CompareChecker();
    }

    public function testCheckIsOk()
    {
        $this->checker->setOption('operator', CompareChecker::IS_EQUAL_TO);
        $this->assertTrue($this->checker->check('1', ['compareTo' => '1']));
        $this->assertTrue($this->checker->check(1, ['compareTo' => 1]));
        $this->assertTrue($this->checker->check('0', ['compareTo' => 0]));
    }

    public function testCheckIsNotEqualIsOk()
    {
        $this->checker->setOption('operator', CompareChecker::IS_NOT_EQUAL);
        $this->assertTrue($this->checker->check('1', ['compareTo' => '0']));
        $this->assertTrue($this->checker->check(1, ['compareTo' => 0]));
        $this->assertTrue($this->checker->check('0', ['compareTo' => 1]));
    }

    public function testCheckIsGreaterThanIsOk()
    {
        $this->checker->setOption('operator', CompareChecker::IS_GREATER_THAN);
        $this->assertTrue($this->checker->check(2, ['compareTo' => 1]));
        $this->assertTrue($this->checker->check('B', ['compareTo' => 'A']));
    }

    public function testCheckIsLessThanIsOk()
    {
        $this->checker->setOption('operator', CompareChecker::IS_LESS_THAN);
        $this->assertTrue($this->checker->check(1, ['compareTo' => 2]));
        $this->assertTrue($this->checker->check('A', ['compareTo' => 'B']));
    }

    public function testCheckIsGreaterThanOrEqualToIsOk()
    {
        $this->checker->setOption('operator', CompareChecker::IS_GREATER_THAN_OR_EQUAL_TO);
        $this->assertTrue($this->checker->check(1, ['compareTo' => 1]));
        $this->assertTrue($this->checker->check(2, ['compareTo' => 1]));
        $this->assertTrue($this->checker->check('A', ['compareTo' => 'A']));
        $this->assertTrue($this->checker->check('B', ['compareTo' => 'A']));
    }

    public function testCheckIsLessThanOrEqualToIsOk()
    {
        $this->checker->setOption('operator', CompareChecker::IS_LESS_THAN_OR_EQUAL_TO);
        $this->assertTrue($this->checker->check(1, ['compareTo' => 1]));
        $this->assertTrue($this->checker->check(1, ['compareTo' => 2]));
        $this->assertTrue($this->checker->check('A', ['compareTo' => 'A']));
        $this->assertTrue($this->checker->check('A', ['compareTo' => 'B']));
    }

    public function testRaiseCheckerExceptionWithCodeInvalidEqualToWhenCheckIsNg()
    {
        $this->expectException(CompareException::class);
        $this->expectExceptionCode(CompareException::INVALID_EQUAL_TO);
        $this->checker->check(1, ['operator' => CompareChecker::IS_EQUAL_TO, 'compareTo' => 2]);
    }

    public function testRaiseCheckerExceptionWithCodeInvalidNotEqualWhenCheckIsNg()
    {
        $this->expectException(CompareException::class);
        $this->expectExceptionCode(CompareException::INVALID_NOT_EQUAL);
        $this->checker->check(1, ['operator' => CompareChecker::IS_NOT_EQUAL, 'compareTo' => 1]);
    }

    public function testRaiseCheckerExceptionWithCodeInvalidGreaterThanWhenCheckIsNg()
    {
        $this->expectException(CompareException::class);
        $this->expectExceptionCode(CompareException::INVALID_GREATER_THAN);
        $this->checker->check(1, ['operator' => CompareChecker::IS_GREATER_THAN, 'compareTo' => 2]);
    }

    public function testRaiseCheckerExceptionWithCodeInvalidLessThanWhenCheckIsNg()
    {
        $this->expectException(CompareException::class);
        $this->expectExceptionCode(CompareException::INVALID_LESS_THAN);
        $this->checker->check(2, ['operator' => CompareChecker::IS_LESS_THAN, 'compareTo' => 1]);
    }

    public function testRaiseCheckerExceptionWithCodeInvalidGreaterThanOrEqualToWhenCheckIsNg()
    {
        $this->expectException(CompareException::class);
        $this->expectExceptionCode(CompareException::INVALID_GREATER_THAN_OR_EQUAL_TO);
        $this->checker->check(1, ['operator' => CompareChecker::IS_GREATER_THAN_OR_EQUAL_TO, 'compareTo' => 2]);
    }

    public function testRaiseCheckerExceptionWithCodeInvalidLessThanOrEqualToWhenCheckIsNg()
    {
        $this->expectException(CompareException::class);
        $this->expectExceptionCode(CompareException::INVALID_LESS_THAN_OR_EQUAL_TO);
        $this->checker->check(2, ['operator' => CompareChecker::IS_LESS_THAN_OR_EQUAL_TO, 'compareTo' => 1]);
    }

    public function testRaiseInvalidArgumentExceptionWhenOperatorParameterIsNotSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check('1', ['operator' => null, 'compareTo' => '2']);
    }

    public function testRaiseInvalidArgumentExceptionWhenCompareValueParameterIsNotSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check('1', ['operator' => CompareChecker::IS_EQUAL_TO]);
    }

    public function testRaiseInvalidArgumentExceptionWhenInvalidOperatorParameterIsSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check('1', ['operator' => 'foo', 'compareTo' => '2']);
    }

    public function testInvokeMethod()
    {
        $this->expectException(CompareException::class);
        $this->expectExceptionCode(CompareException::INVALID_EQUAL_TO);
        $checker = $this->checker;
        $checker->setOption('operator', CompareChecker::IS_EQUAL_TO);
        $checker->setOption('compareTo', '2');
        $checker('1');
    }

}
