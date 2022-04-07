<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\GraphChecker;
use Volcanus\Validation\Exception\CheckerException\GraphException;

/**
 * GraphCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class GraphCheckerTest extends TestCase
{

    /** @var  GraphChecker */
    protected $checker;

    public function setUp(): void
    {
        $this->checker = new GraphChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('+ABC123.@#%'));
    }

    public function testRaiseGraphExceptionWhenCheckIsNgByFormat()
    {
        $this->expectException(GraphException::class);
        $this->checker->check("ABC\n\r\t");
    }

    public function testInvokeMethod()
    {
        $this->expectException(GraphException::class);
        $checker = $this->checker;
        $checker("ABC\n\r\t");
    }

}
