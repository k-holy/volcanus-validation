<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Test\Checker;

use Volcanus\Validation\Checker\ChoiceChecker;

/**
 * ChoiceCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class ChoiceCheckerTest extends \PHPUnit_Framework_TestCase
{

	protected $checker;

	public function setUp()
	{
		$this->checker = new ChoiceChecker();
	}

	public function testCheckIsOk()
	{
		$this->assertTrue($this->checker->check('1', array('choices' => array('1','2','3'))));
		$this->assertTrue($this->checker->check('2', array('choices' => array('1','2','3'))));
		$this->assertTrue($this->checker->check('3', array('choices' => array('1','2','3'))));
		$this->assertTrue($this->checker->check('1', array('choices' => '1,2,3')));
		$this->assertTrue($this->checker->check('2', array('choices' => '1,2,3')));
		$this->assertTrue($this->checker->check('3', array('choices' => '1,2,3')));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\ChoiceException
	 */
	public function testRaiseCheckerExceptionWhenCheckIsNgByInvalidChoice()
	{
		$this->checker->check('4', array('choices' => array('1','2','3')));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRaiseInvalidArgumentExceptionWhenInvalidChoicesParameterIsSpecified()
	{
		$this->checker->check('1', array('choices' => 'Foo'));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\ChoiceException
	 */
	public function testInvokeMethod()
	{
		$checker = $this->checker;
		$checker->setOption('choices', array('1','2','3'));
		$checker('4');
	}

}
