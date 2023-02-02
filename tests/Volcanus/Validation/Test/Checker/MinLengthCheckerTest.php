<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker;
use Volcanus\Validation\Checker\MinLengthChecker;
use Volcanus\Validation\Exception\CheckerException\MinLengthException;

/**
 * MinLengthCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class MinLengthCheckerTest extends TestCase
{

    /** @var  MinLengthChecker */
    protected MinLengthChecker $checker;

    public function setUp(): void
    {
        $this->checker = new MinLengthChecker();
        $this->checker->setOption('encoding', 'UTF-8');
        $this->checker->setOption('mbLength', Checker::LENGTH_CHARS);
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('1', ['length' => 1]));
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOkWhenMultiByteCharactersLength()
    {
        $this->assertTrue($this->checker->check('ｱｲｳ', ['length' => 3]));
        $this->assertTrue($this->checker->check('アイウ', ['length' => 3]));
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
    public function testRaiseMinLengthExceptionWhenHalfWidthedMultiByteCharactersLength()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('ｱｲｳ', ['length' => 4]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinLengthExceptionWhenFullWidthedMultiByteCharactersLength()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('アイウ', ['length' => 4]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinLengthExceptionWhenHalfWidthedMultiByteCharactersBytes()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('ｱｲｳ', ['length' => 10, 'mbLength' => Checker::LENGTH_BYTES]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinLengthExceptionWhenFullWidthedMultiByteCharactersBytes()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('アイウ', ['length' => 10, 'mbLength' => Checker::LENGTH_BYTES]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinLengthExceptionWhenHalfWidthedMultiByteCharactersWidth()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('ｱｲｳ', ['length' => 4, 'mbLength' => Checker::LENGTH_WIDTH]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinLengthExceptionWhenFullWidthedMultiByteCharactersWidth()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('アイウ', ['length' => 7, 'mbLength' => Checker::LENGTH_WIDTH]);
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
        $this->expectException(MinLengthException::class);
        $checker = $this->checker;
        $checker->setOption('length', 4);
        $checker('123');
    }

}
