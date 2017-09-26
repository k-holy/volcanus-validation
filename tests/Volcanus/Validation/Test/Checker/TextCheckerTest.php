<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\TextChecker;

/**
 * TextCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class TextCheckerTest extends \PHPUnit_Framework_TestCase
{

    protected $checker;

    public function setUp()
    {
        $this->checker = new TextChecker();
        $this->checker->setOption('encoding', 'UTF-8');
        $this->checker->setOption('mbLength', TextChecker::LENGTH_CHARS);
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('1', array('maxLength' => 1)));
    }

    public function testCheckIsOkWhenMultiByteCharactersLength()
    {
        $this->assertTrue($this->checker->check('ｱｲｳ', array('maxLength' => 3)));
        $this->assertTrue($this->checker->check('アイウ', array('maxLength' => 3)));
    }

    public function testRaiseMaxlengthExceptionWhenckIsOkWhenMultiByteCharactersBytes()
    {
        $this->assertTrue($this->checker->check('ｱｲｳ', array(
            'maxLength' => 9,
            'mbLength' => TextChecker::LENGTH_BYTES,
        )));
        $this->assertTrue($this->checker->check('アイウ', array(
            'maxLength' => 9,
            'mbLength' => TextChecker::LENGTH_BYTES,
        )));
    }

    public function testCheckIsOkWhenMultiByteCharactersWidth()
    {
        $this->assertTrue($this->checker->check('ｱｲｳ', array(
            'maxLength' => 3,
            'mbLength' => TextChecker::LENGTH_WIDTH,
        )));
        $this->assertTrue($this->checker->check('アイウ', array(
            'maxLength' => 6,
            'mbLength' => TextChecker::LENGTH_WIDTH,
        )));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenHalfWidthedMultiByteCharactersLength()
    {
        $this->checker->check('ｱｲｳ', array('minLength' => 4));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenFullWidthedMultiByteCharactersLength()
    {
        $this->checker->check('アイウ', array('minLength' => 4));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenHalfWidthedMultiByteCharactersBytes()
    {
        $this->checker->check('ｱｲｳ', array('minLength' => 10, 'mbLength' => TextChecker::LENGTH_BYTES));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenFullWidthedMultiByteCharactersBytes()
    {
        $this->checker->check('アイウ', array('minLength' => 10, 'mbLength' => TextChecker::LENGTH_BYTES));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenHalfWidthedMultiByteCharactersWidth()
    {
        $this->checker->check('ｱｲｳ', array('minLength' => 4, 'mbLength' => TextChecker::LENGTH_WIDTH));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MinLengthException
     */
    public function testRaiseMinLengthExceptionWhenFullWidthedMultiByteCharactersWidth()
    {
        $this->checker->check('アイウ', array('minLength' => 7, 'mbLength' => TextChecker::LENGTH_WIDTH));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MaxLengthException
     */
    public function testRaiseMaxLengthExceptionWhenHalfWidthedMultiByteCharactersLength()
    {
        $this->checker->check('ｱｲｳ', array('maxLength' => 2));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MaxLengthException
     */
    public function testRaiseMaxLengthExceptionWhenFullWidthedMultiByteCharactersLength()
    {
        $this->checker->check('アイウ', array('maxLength' => 2));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MaxLengthException
     */
    public function testRaiseMaxLengthExceptionWhenHalfWidthedMultiByteCharactersBytes()
    {
        $this->checker->check('ｱｲｳ', array('maxLength' => 8, 'mbLength' => TextChecker::LENGTH_BYTES));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MaxLengthException
     */
    public function testRaiseMaxLengthExceptionWhenFullWidthedMultiByteCharactersBytes()
    {
        $this->checker->check('アイウ', array('maxLength' => 8, 'mbLength' => TextChecker::LENGTH_BYTES));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MaxLengthException
     */
    public function testRaiseMaxLengthExceptionWhenHalfWidthedMultiByteCharactersWidth()
    {
        $this->checker->check('ｱｲｳ', array('maxLength' => 2, 'mbLength' => TextChecker::LENGTH_WIDTH));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MaxLengthException
     */
    public function testRaiseMaxLengthExceptionWhenFullWidthedMultiByteCharactersWidth()
    {
        $this->checker->check('アイウ', array('maxLength' => 5, 'mbLength' => TextChecker::LENGTH_WIDTH));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MaxLengthException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker->setOption('maxLength', 2);
        $checker('123');
    }

}
