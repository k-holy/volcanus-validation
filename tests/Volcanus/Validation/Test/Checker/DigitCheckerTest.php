<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\DigitChecker;

/**
 * DigitCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class DigitCheckerTest extends \PHPUnit_Framework_TestCase
{

    protected $checker;

    public function setUp()
    {
        $this->checker = new DigitChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('0123'));
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\DigitException
     */
    public function testRaiseDigitExceptionWhenCheckIsNgByFormat()
    {
        $this->checker->check('+123.45');
    }

    /**
     * @expectedException Volcanus\Validation\Exception\CheckerException\DigitException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker('+123.45');
    }

}
