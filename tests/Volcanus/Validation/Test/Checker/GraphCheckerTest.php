<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\GraphChecker;

/**
 * GraphCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class GraphCheckerTest extends \PHPUnit_Framework_TestCase
{

    /** @var  \Volcanus\Validation\Checker\GraphChecker */
    protected $checker;

    public function setUp()
    {
        $this->checker = new GraphChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('+ABC123.@#%'));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\GraphException
     */
    public function testRaiseGraphExceptionWhenCheckIsNgByFormat()
    {
        $this->checker->check("ABC\n\r\t");
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\GraphException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker("ABC\n\r\t");
    }

}
