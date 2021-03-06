<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\UriChecker;

/**
 * UriCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class UriCheckerTest extends \PHPUnit\Framework\TestCase
{

    /** @var  \Volcanus\Validation\Checker\UriChecker */
    protected $checker;

    public function setUp()
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

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\UriException
     */
    public function testRaiseUriExceptionWhenCheckIsNgByFormat()
    {
        $this->checker->check('::');
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\UriException
     */
    public function testRaiseUriExceptionWhenCheckIsNgBySchemeIsNotAccepted()
    {
        $this->checker->check('ftp://www.example.com/', ['acceptScheme' => 'http,https']);
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\UriException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker->setOption('acceptScheme', 'http,https');
        $checker('ftp://www.example.com/');
    }

}
