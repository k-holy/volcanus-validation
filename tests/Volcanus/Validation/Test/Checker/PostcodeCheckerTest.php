<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\PostcodeChecker;
use Volcanus\Validation\Exception\CheckerException\PostcodeException;

/**
 * PostcodeCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class PostcodeCheckerTest extends TestCase
{

    /** @var  PostcodeChecker */
    protected PostcodeChecker $checker;

    public function setUp(): void
    {
        $this->checker = new PostcodeChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('658-0032'));
    }

    public function testCheckIsOkWithJpLocale()
    {
        $this->assertTrue($this->checker->check('658-0032', ['locale' => 'jp']));
    }

    public function testRaisePostcodeExceptionWhenCheckIsNgByFormat()
    {
        $this->expectException(PostcodeException::class);
        $this->checker->check('a');
    }

    public function testInvokeMethod()
    {
        $this->expectException(PostcodeException::class);
        $checker = $this->checker;
        $checker('658');
    }

}
