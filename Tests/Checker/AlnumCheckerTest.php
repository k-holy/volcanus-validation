<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Tests\Checker;

use Volcanus\Validation\Checker\AlnumChecker;

/**
 * AlnumCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class AlnumCheckerTest extends \PHPUnit_Framework_TestCase
{

	protected $checker;

	public function setUp()
	{
		$this->checker = new AlnumChecker();
	}

	public function testCheckIsOk()
	{
		$this->assertTrue($this->checker->check('ABC123'));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\AlnumException
	 */
	public function testRaiseAlnumExceptionWhenCheckIsNgByFormat()
	{
		$this->checker->check('+123.45');
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\AlnumException
	 */
	public function testInvokeMethod()
	{
		$checker = $this->checker;
		$checker('+123.45');
	}

}
