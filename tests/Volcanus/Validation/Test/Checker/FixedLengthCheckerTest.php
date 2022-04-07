<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\FixedLengthChecker;
use Volcanus\Validation\Exception\CheckerException\MaxLengthException;
use Volcanus\Validation\Exception\CheckerException\MinLengthException;

/**
 * FixedLengthCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class FixedLengthCheckerTest extends TestCase
{

    /** @var  FixedLengthChecker */
    protected $checker;

    public function setUp(): void
    {
        $this->checker = new FixedLengthChecker();
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('1', ['length' => 1]));
        $this->assertTrue($this->checker->check('123', ['length' => 3]));
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenLengthOfTheValueIsLongerThanMaxLength()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('123', ['length' => 2]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinLengthExceptionWhenLengthOfTheValueIsShorterThanMinLength()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('123', ['length' => 4]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseInvalidArgumentExceptionWhenLengthParameterIsNotSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check('123');
    }

    public function testInvokeMethod()
    {
        $this->expectException(MaxLengthException::class);
        $checker = $this->checker;
        $checker->setOption('length', 2);
        $checker('123');
    }

}
