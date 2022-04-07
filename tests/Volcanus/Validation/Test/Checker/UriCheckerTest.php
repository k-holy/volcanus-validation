<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\UriChecker;
use Volcanus\Validation\Exception\CheckerException\UriException;

/**
 * UriCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class UriCheckerTest extends TestCase
{

    /** @var  UriChecker */
    protected $checker;

    public function setUp(): void
    {
        $this->checker = new UriChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('http://www.example.com/'));
    }

    public function testCheckIsOkWithAcceptSchemes()
    {
        $this->assertTrue($this->checker->check('https://www.example.com/', ['acceptScheme' => 'https']));
        $this->assertTrue($this->checker->check('http://userinfo@reg-name:8080/path/to/file.ext?foo=bar#1'));
        $this->assertTrue($this->checker->check('http://www.example.com/'));
        $this->assertTrue($this->checker->check('http://日本語ドメイン.com/'));
    }

    public function testRaiseUriExceptionWhenCheckIsNgByFormat()
    {
        $this->expectException(UriException::class);
        $this->checker->check('::');
    }

    public function testRaiseUriExceptionWhenCheckIsNgBySchemeIsNotAccepted()
    {
        $this->expectException(UriException::class);
        $this->checker->check('ftp://www.example.com/', ['acceptScheme' => 'http,https']);
    }

    public function testInvokeMethod()
    {
        $this->expectException(UriException::class);
        $checker = $this->checker;
        $checker->setOption('acceptScheme', 'http,https');
        $checker('ftp://www.example.com/');
    }

}
