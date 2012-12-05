<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Tests\Checker;

use Volcanus\Validation\Checker\AcceptCharactersChecker;

/**
 * AcceptCharactersCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class AcceptCharactersCheckerTest extends \PHPUnit_Framework_TestCase
{

	protected $checker;

	public function setUp()
	{
		$this->checker = new AcceptCharactersChecker();
	}

	private function getAcceptCharacters()
	{
		return 'ABC123+-=@{}';
	}

	public function testCheckIsOk()
	{
		$options = array(
			'acceptCharacters' => $this->getAcceptCharacters(),
		);
		$this->assertTrue($this->checker->check('ABC'         , $options));
		$this->assertTrue($this->checker->check('123'         , $options));
		$this->assertTrue($this->checker->check('+-=@{}'      , $options));
		$this->assertTrue($this->checker->check('A1+=B2-@C3{}', $options));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\AcceptCharactersException
	 */
	public function testRaiseAcceptCharactersExceptionWhenCharacterOtherThanAcceptCharactersAreContained()
	{
		$this->checker->check('A1+=B2-@C3{}%', array(
			'acceptCharacters' => $this->getAcceptCharacters(),
		));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\AcceptCharactersException
	 */
	public function testInvokeMethod()
	{
		$checker = $this->checker;
		$checker('A1+=B2-@C3{}%', array(
			'acceptCharacters' => $this->getAcceptCharacters(),
		));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRaiseInvalidArgumentExceptionWhenAcceptCharactersParameterIsNotSpecified()
	{
		$this->checker->check('A1+=B2-@C3{}');
	}

}