<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\AlphaChecker;

/**
 * AlphaCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class AlphaCheckerTest extends \PHPUnit_Framework_TestCase
{

    /** @var  \Volcanus\Validation\Checker\AlphaChecker */
    protected $checker;

    public function setUp()
    {
        $this->checker = new AlphaChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('ABC'));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\AlphaException
     */
    public function testRaiseAlphaExceptionWhenCheckIsNgByFormat()
    {
        $this->checker->check('123');
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\AlphaException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker('123');
    }

}
