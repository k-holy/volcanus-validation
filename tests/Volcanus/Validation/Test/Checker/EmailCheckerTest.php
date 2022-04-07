<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\EmailChecker;
use Volcanus\Validation\Exception\CheckerException\EmailException;
use Volcanus\Validation\Exception\CheckerException\MaxLengthException;

/**
 * EmailCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class EmailCheckerTest extends TestCase
{

    /** @var  EmailChecker */
    protected $checker;

    public function setUp(): void
    {
        $this->checker = new EmailChecker();
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('Abc.123@example.com'));
        $this->assertTrue($this->checker->check('user+mailbox/department=shipping@example.com'));
        $this->assertTrue($this->checker->check('!#$%&\'*+-/=?^_`.{|}~@example.com'));
        $this->assertTrue($this->checker->check('foo@127.0.0.1'));
        $this->assertTrue($this->checker->check('foo@localhost'));
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOkWhenLocalPartIncludesQuotedString()
    {
        $this->assertTrue($this->checker->check('"Abc@def"@example.com'));
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOkWhenLocalPartIncludesQuotedStringWithQuotedPair()
    {
        $this->assertTrue($this->checker->check('"Fred\ Bloggs"@example.com'));
        $this->assertTrue($this->checker->check('"Joe.\\Blow"@example.com'));
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOkWhenLocalPartIsNameAddrFormat()
    {
        $this->assertTrue($this->checker->check('"テスト"<foo@example.com>'));
    }

    /**
     * @throws \Exception
     */
    public function testCheckIsOkWhenAllowDotEndOfLocalPart()
    {
        $this->assertTrue($this->checker->check('foo.@example.com', ['allowDotEndOfLocalPart' => true]));
    }

    /**
     * @throws \Exception
     */
    public function testRaiseEmailExceptionWhenNotAllowDotEndOfLocalPart()
    {
        $this->expectException(EmailException::class);
        $this->expectExceptionCode(EmailException::INVALID_LOCAL_PART);
        $this->checker->check('foo.@example.com');
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenInvalidAddrSpecOver256Bytes()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check(str_repeat('a', 64) . '@' . str_repeat('a', 188) . '.com');
    }

    /**
     * @throws \Exception
     */
    public function testRaiseEmailExceptionWhenInvalidLocalPart()
    {
        $this->expectException(EmailException::class);
        $this->expectExceptionCode(EmailException::INVALID_LOCAL_PART);
        $this->checker->check('(-_-)@example.com');
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenLocalPartIsOver64Bytes()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check(str_repeat('a', 65) . '@example.com');
    }

    /**
     * @throws \Exception
     */
    public function testRaiseEmailExceptionWhenInvalidDomain()
    {
        $this->expectException(EmailException::class);
        $this->expectExceptionCode(EmailException::INVALID_DOMAIN);
        $this->checker->check('foo@(-_-).com');
    }

    /**
     * @throws \Exception
     */
    public function testRaiseMaxLengthExceptionWhenDomainIsOver255Bytes()
    {
        $this->expectException(MaxLengthException::class);
        $this->checker->check('foo@' . str_repeat('a', 252) . '.com');
    }

    public function testInvokeMethod()
    {
        $this->expectException(EmailException::class);
        $this->expectExceptionCode(EmailException::INVALID_LOCAL_PART);
        $checker = $this->checker;
        $checker('foo.@example.com');
    }

}
