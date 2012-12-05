<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Tests\Checker;

use Volcanus\Validation\Checker\PhoneChecker;

/**
 * PhoneCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class PhoneCheckerTest extends \PHPUnit_Framework_TestCase
{

	protected $checker;

	public function setUp()
	{
		$this->checker = new PhoneChecker();
	}

	public function testCheckIsOk()
	{
		$this->assertTrue($this->checker->check('+81-6-584-2222'));
	}

	public function testCheckIsOkWithJpLocale()
	{
		$this->assertTrue($this->checker->check('06-584-2222', array('locale' => 'jp')));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\PhoneException
	 */
	public function testRaisePhoneExceptionWhenCheckIsNgByFormat()
	{
		$this->checker->check('-5');
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\PhoneException
	 */
	public function testInvokeMethod()
	{
		$checker = $this->checker;
		$checker->setOption('locale', 'jp');
		$checker('+81-6-584-2222');
	}

}