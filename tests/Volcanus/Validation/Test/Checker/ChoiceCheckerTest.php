<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\ChoiceChecker;

/**
 * ChoiceCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class ChoiceCheckerTest extends \PHPUnit_Framework_TestCase
{

    /** @var  \Volcanus\Validation\Checker\ChoiceChecker */
    protected $checker;

    public function setUp()
    {
        $this->checker = new ChoiceChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('1', array('choices' => array('1', '2', '3'))));
        $this->assertTrue($this->checker->check('2', array('choices' => array('1', '2', '3'))));
        $this->assertTrue($this->checker->check('3', array('choices' => array('1', '2', '3'))));
        $this->assertTrue($this->checker->check('1', array('choices' => '1,2,3')));
        $this->assertTrue($this->checker->check('2', array('choices' => '1,2,3')));
        $this->assertTrue($this->checker->check('3', array('choices' => '1,2,3')));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\ChoiceException
     */
    public function testRaiseCheckerExceptionWhenCheckIsNgByInvalidChoice()
    {
        $this->checker->check('4', array('choices' => array('1', '2', '3')));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenInvalidChoicesParameterIsSpecified()
    {
        $this->checker->check('1', array('choices' => 'Foo'));
    }

    /**
     * @expectedException \Volcanus\Validation\Exception\CheckerException\ChoiceException
     */
    public function testInvokeMethod()
    {
        $checker = $this->checker;
        $checker->setOption('choices', array('1', '2', '3'));
        $checker('4');
    }

}
