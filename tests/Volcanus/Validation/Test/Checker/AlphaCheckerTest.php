<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\AlphaChecker;
use Volcanus\Validation\Exception\CheckerException\AlphaException;

/**
 * AlphaCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class AlphaCheckerTest extends TestCase
{

    /** @var  AlphaChecker */
    protected $checker;

    public function setUp(): void
    {
        $this->checker = new AlphaChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('ABC'));
    }

    public function testRaiseAlphaExceptionWhenCheckIsNgByFormat()
    {
        $this->expectException(AlphaException::class);
        $this->checker->check('123');
    }

    public function testInvokeMethod()
    {
        $this->expectException(AlphaException::class);
        $checker = $this->checker;
        $checker('123');
    }

}
