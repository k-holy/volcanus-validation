<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\EmailChecker;

/**
 * EmailCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class EmailCheckerTest extends \PHPUnit_Framework_TestCase
{

    protected $checker;

    public function setUp()
    {
        $this->checker = new EmailChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('Abc.123@example.com'));
        $this->assertTrue($this->checker->check('user+mailbox/department=shipping@example.com'));
        $this->assertTrue($this->checker->check('!#$%&\'*+-/=?^_`.{|}~@example.com'));
        $this->assertTrue($this->checker->check('foo@127.0.0.1'));
        $this->assertTrue($this->checker->check('foo@localhost'));
    }

    public function testCheckIsOkWhenLocalPartIncludesQuotedString()
    {
        $this->assertTrue($this->checker->check('"Abc@def"@example.com'));
    }

    public function testCheckIsOkWhenLocalPartIncludesQuotedStringWithQuotedPair()
    {
        $this->assertTrue($this->checker->check('"Fred\ Bloggs"@example.com'));
        $this->assertTrue($this->checker->check('"Joe.\\Blow"@example.com'));
    }

    public function testCheckIsOkWhenLocalPartIsNameAddrFormat()
    {
        $this->assertTrue($this->checker->check('"テスト"<foo@example.com>'));
    }

    public function testCheckIsOkWhenAllowDotEndOfLocalPart()
    {
        $this->assertTrue($this->checker->check('foo.@example.com', array('allowDotEndOfLocalPart' => true)));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\EmailException
     * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\EmailException::INVALID_LOCAL_PART
     */
    public function testRaiseEmailExceptionWhenNotAllowDotEndOfLocalPart()
    {
        $this->checker->check('foo.@example.com');
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MaxLengthException
     */
    public function testRaiseMaxLengthExceptionWhenInvalidAddrSpecOver256Bytes()
    {
        $this->checker->check(str_repeat('a', 64) . '@' . str_repeat('a', 188) . '.com');
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\EmailException
     * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\EmailException::INVALID_LOCAL_PART
     */
    public function testRaiseEmailExceptionWhenInvalidLocalPart()
    {
        $this->checker->check('(-_-)@example.com');
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MaxLengthException
     */
    public function testRaiseMaxLengthExceptionWhenLocalPartIsOver64Bytes()
    {
        $this->checker->check(str_repeat('a', 65) . '@example.com');
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\EmailException
     * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\EmailException::INVALID_DOMAIN
     */
    public function testRaiseEmailExceptionWhenInvalidDomain()
    {
        $this->checker->check('foo@(-_-).com');
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\MaxLengthException
     */
    public function testRaiseMaxLengthExceptionWhenDomainIsOver255Bytes()
    {
        $this->checker->check('foo@' . str_repeat('a', 252) . '.com');
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\EmailException
     * @expectedExceptionCode Volcanus\Validation\Exception\CheckerException\EmailException::INVALID_LOCAL_PART
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker('foo.@example.com');
    }

}
