<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\AlnumChecker;
use Volcanus\Validation\Exception\CheckerException\AlnumException;

/**
 * AlnumCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class AlnumCheckerTest extends TestCase
{

    /** @var  AlnumChecker */
    protected $checker;

    public function setUp(): void
    {
        $this->checker = new AlnumChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('ABC123'));
    }

    public function testRaiseAlnumExceptionWhenCheckIsNgByFormat()
    {
        $this->expectException(AlnumException::class);
        $this->checker->check('+123.45');
    }

    public function testInvokeMethod()
    {
        $this->expectException(AlnumException::class);
        $checker = $this->checker;
        $checker('+123.45');
    }

}
