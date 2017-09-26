<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\PostcodeChecker;

/**
 * PostcodeCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class PostcodeCheckerTest extends \PHPUnit_Framework_TestCase
{

    /** @var  \Volcanus\Validation\Checker\PostcodeChecker */
    protected $checker;

    public function setUp()
    {
        $this->checker = new PostcodeChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('658-0032'));
    }

    public function testCheckIsOkWithJpLocale()
    {
        $this->assertTrue($this->checker->check('658-0032', array('locale' => 'jp')));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\PostcodeException
     */
    public function testRaisePostcodeExceptionWhenCheckIsNgByFormat()
    {
        $this->checker->check('a');
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\PostcodeException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker('658');
    }

}
