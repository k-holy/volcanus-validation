<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker;
use Volcanus\Validation\Checker\MaxLengthChecker;
use Volcanus\Validation\Exception\CheckerException\MaxLengthException;

/**
 * MaxLengthCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class MaxLengthCheckerTest extends TestCase
{

    /** @var  MaxLengthChecker */
    protected $checker;

    public function setUp(): void
    {
        $this->checker = new MaxLengthChecker();
        $this->checker->setOption('encoding', 'UTF-8');
        $this->checker->setOption('mbLength', Checker::LENGTH_CHARS);
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOk()
    {
        $this->assertTrue(
            $this->checker->check('1', ['length' => 1])
        );
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOkWhenMultiByteCharactersLength()
    {
        $this->assertTrue(
            $this->checker->check('ｱｲｳ', ['length' => 3])
        );
        $this->assertTrue(
            $this->checker->check('アイウ', ['length' => 3])
        );
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOkWhenMultiByteCharactersBytes()
    {
        $this->assertTrue(
            $this->checker->check('ｱｲｳ', ['length' => 9, 'mbLength' => Checker::LENGTH_BYTES])
        );
        $this->assertTrue(
            $this->checker->check('アイウ', ['length' => 9, 'mbLength' => Checker::LENGTH_BYTES])
        );
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOkWhenMultiByteCharactersWidth()
    {
        $this->assertTrue(
            $this->checker->check('ｱｲｳ', ['length' => 3, 'mbLength' => Checker::LENGTH_WIDTH])
        );
        $this->assertTrue(
            $this->checker->check('アイウ', ['length' => 6, 'mbLength' => Checker::LENGTH_WIDTH])
        );
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenHalfWidthedMultiByteCharactersLength()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('ｱｲｳ', ['length' => 2]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenFullWidthedMultiByteCharactersLength()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('アイウ', ['length' => 2]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenHalfWidthedMultiByteCharactersBytes()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('ｱｲｳ', ['length' => 8, 'mbLength' => Checker::LENGTH_BYTES]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenFullWidthedMultiByteCharactersBytes()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('アイウ', ['length' => 8, 'mbLength' => Checker::LENGTH_BYTES]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenHalfWidthedMultiByteCharactersWidth()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('ｱｲｳ', ['length' => 2, 'mbLength' => Checker::LENGTH_WIDTH]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenFullWidthedMultiByteCharactersWidth()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('アイウ', ['length' => 5, 'mbLength' => Checker::LENGTH_WIDTH]);
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
