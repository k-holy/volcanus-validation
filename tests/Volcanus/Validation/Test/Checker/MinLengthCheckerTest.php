<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\MinLengthChecker;

/**
 * MinLengthCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class MinLengthCheckerTest extends \PHPUnit\Framework\TestCase
{

    /** @var  \Volcanus\Validation\Checker\MinLengthChecker */
    protected $checker;

    public function setUp()
    {
        $this->checker = new MinLengthChecker();
        $this->checker->setOption('encoding', 'UTF-8');
        $this->checker->setOption('mbLength', MinLengthChecker::LENGTH_CHARS);
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('1', ['length' => 1]));
    }

    public function testCheckIsOkWhenMultiByteCharactersLength()
    {
        $this->assertTrue($this->checker->check('ｱｲｳ', ['length' => 3]));
        $this->assertTrue($this->checker->check('アイウ', ['length' => 3]));
    }

    public function testCheckIsOkWhenMultiByteCharactersBytes()
    {
        $this->assertTrue($this->checker->check('ｱｲｳ', ['length' => 9, 'mbLength' => MinLengthChecker::LENGTH_BYTES]));
        $this->assertTrue($this->checker->check('アイウ', ['length' => 9, 'mbLength' => MinLengthChecker::LENGTH_BYTES]));
    }

    public function testCheckIsOkWhenMultiByteCharactersWidth()
    {
        $this->assertTrue($this->checker->check('ｱｲｳ', ['length' => 3, 'mbLength' => MinLengthChecker::LENGTH_WIDTH]));
        $this->assertTrue($this->checker->check('アイウ', ['length' => 6, 'mbLength' => MinLengthChecker::LENGTH_WIDTH]));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenHalfWidthedMultiByteCharactersLength()
    {
        $this->checker->check('ｱｲｳ', ['length' => 4]);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenFullWidthedMultiByteCharactersLength()
    {
        $this->checker->check('アイウ', ['length' => 4]);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenHalfWidthedMultiByteCharactersBytes()
    {
        $this->checker->check('ｱｲｳ', ['length' => 10, 'mbLength' => MinLengthChecker::LENGTH_BYTES]);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenFullWidthedMultiByteCharactersBytes()
    {
        $this->checker->check('アイウ', ['length' => 10, 'mbLength' => MinLengthChecker::LENGTH_BYTES]);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenHalfWidthedMultiByteCharactersWidth()
    {
        $this->checker->check('ｱｲｳ', ['length' => 4, 'mbLength' => MinLengthChecker::LENGTH_WIDTH]);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenFullWidthedMultiByteCharactersWidth()
    {
        $this->checker->check('アイウ', ['length' => 7, 'mbLength' => MinLengthChecker::LENGTH_WIDTH]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenLengthParameterIsNotSpecified()
    {
        $this->checker->check('123');
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker->setOption('length', 4);
        $checker('123');
    }

}
