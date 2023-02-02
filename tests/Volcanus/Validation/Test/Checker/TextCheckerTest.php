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
use Volcanus\Validation\Checker\TextChecker;
use Volcanus\Validation\Exception\CheckerException\MaxLengthException;
use Volcanus\Validation\Exception\CheckerException\MinLengthException;

/**
 * TextCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class TextCheckerTest extends TestCase
{

    /** @var  TextChecker */
    protected TextChecker $checker;

    public function setUp(): void
    {
        $this->checker = new TextChecker();
        $this->checker->setOption('encoding', 'UTF-8');
        $this->checker->setOption('mbLength', Checker::LENGTH_CHARS);
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('1', ['maxLength' => 1]));
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOkWhenMultiByteCharactersLength()
    {
        $this->assertTrue($this->checker->check('ｱｲｳ', ['maxLength' => 3]));
        $this->assertTrue($this->checker->check('アイウ', ['maxLength' => 3]));
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxlengthExceptionWhenckIsOkWhenMultiByteCharactersBytes()
    {
        $this->assertTrue(
            $this->checker->check('ｱｲｳ', ['maxLength' => 9, 'mbLength' => Checker::LENGTH_BYTES])
        );
        $this->assertTrue(
            $this->checker->check('アイウ', ['maxLength' => 9, 'mbLength' => Checker::LENGTH_BYTES])
        );
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOkWhenMultiByteCharactersWidth()
    {
        $this->assertTrue(
            $this->checker->check('ｱｲｳ', ['maxLength' => 3, 'mbLength' => Checker::LENGTH_WIDTH])
        );
        $this->assertTrue(
            $this->checker->check('アイウ', ['maxLength' => 6, 'mbLength' => Checker::LENGTH_WIDTH])
        );
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinLengthExceptionWhenHalfWidthedMultiByteCharactersLength()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('ｱｲｳ', ['minLength' => 4]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinLengthExceptionWhenFullWidthedMultiByteCharactersLength()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('アイウ', ['minLength' => 4]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinLengthExceptionWhenHalfWidthedMultiByteCharactersBytes()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('ｱｲｳ', ['minLength' => 10, 'mbLength' => Checker::LENGTH_BYTES]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinLengthExceptionWhenFullWidthedMultiByteCharactersBytes()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('アイウ', ['minLength' => 10, 'mbLength' => Checker::LENGTH_BYTES]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinLengthExceptionWhenHalfWidthedMultiByteCharactersWidth()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('ｱｲｳ', ['minLength' => 4, 'mbLength' => Checker::LENGTH_WIDTH]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMinLengthExceptionWhenFullWidthedMultiByteCharactersWidth()
    {
        $this->expectException(MinLengthException::class);
        $this->checker->check('アイウ', ['minLength' => 7, 'mbLength' => Checker::LENGTH_WIDTH]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenHalfWidthedMultiByteCharactersLength()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('ｱｲｳ', ['maxLength' => 2]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenFullWidthedMultiByteCharactersLength()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('アイウ', ['maxLength' => 2]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenHalfWidthedMultiByteCharactersBytes()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('ｱｲｳ', ['maxLength' => 8, 'mbLength' => Checker::LENGTH_BYTES]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenFullWidthedMultiByteCharactersBytes()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('アイウ', ['maxLength' => 8, 'mbLength' => Checker::LENGTH_BYTES]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenHalfWidthedMultiByteCharactersWidth()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('ｱｲｳ', ['maxLength' => 2, 'mbLength' => Checker::LENGTH_WIDTH]);
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenFullWidthedMultiByteCharactersWidth()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('アイウ', ['maxLength' => 5, 'mbLength' => Checker::LENGTH_WIDTH]);
    }

    public function testInvokeMethod()
    {
        $this->expectException(MaxLengthException::class);
        $checker = $this->checker;
        $checker->setOption('maxLength', 2);
        $checker('123');
    }

}
