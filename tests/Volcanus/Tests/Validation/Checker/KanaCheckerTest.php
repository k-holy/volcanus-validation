<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Tests\Validation\Checker;

use Volcanus\Validation\Checker\KanaChecker;

/**
 * KanaCheckerTest
 *
 * @author     k.holy74@gmail.com
 */
class KanaCheckerTest extends \PHPUnit_Framework_TestCase
{

	const ZENKAKU_HIRA = 'ぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをん';
	const ZENKAKU_KANA = 'ァアィイゥウェエォオカガキギクグケゲコゴサザシジスズセゼソゾタダチヂッツヅテデトドナニヌネノハバパヒビピフブプヘベペホボポマミムメモャヤュユョヨラリルレロヮワヰヱヲンヴヵヶ';
	const ZENKAKU_KIGO = '・ーヽヾ';
	const HANKAKU_KANA = '｡｢｣､･ｦｧｨｩｪｫｬｭｮｯｰｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜﾝﾞﾟ';

	protected $checker;
	protected $internalEncoding;

	public function setUp()
	{
		$this->checker = new KanaChecker();
		$this->checker->setOption('encoding', 'UTF-8');
	}

	public function testCheckIsOk()
	{
		$checker = $this->checker;
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA));
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA . self::ZENKAKU_KIGO, array('acceptFlag' => 'H')));
		$this->assertTrue($this->checker->check(self::ZENKAKU_KANA . self::ZENKAKU_KIGO, array('acceptFlag' => 'K')));
		$this->assertTrue($this->checker->check(self::HANKAKU_KANA                     , array('acceptFlag' => 'k')));
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA . self::ZENKAKU_KANA . self::ZENKAKU_KIGO, array('acceptFlag' => 'HK')));
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA . self::HANKAKU_KANA . self::ZENKAKU_KIGO, array('acceptFlag' => 'Hk')));
		$this->assertTrue($this->checker->check(self::ZENKAKU_KANA . self::HANKAKU_KANA . self::ZENKAKU_KIGO, array('acceptFlag' => 'Kk')));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\KanaException
	 */
	public function testRaiseKanaExceptionWhenCheckIsNgByFormat()
	{
		$this->checker->check('ｚ');
	}

	public function testCheckIsOkEnableSpace()
	{
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA  . ' ', array('acceptSpace' => true)));
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA  . self::ZENKAKU_KIGO . ' ', array('acceptFlag' => 'H', 'acceptSpace' => true)));
		$this->assertTrue($this->checker->check(self::ZENKAKU_KANA  . self::ZENKAKU_KIGO . ' ', array('acceptFlag' => 'K', 'acceptSpace' => true)));
		$this->assertTrue($this->checker->check(self::HANKAKU_KANA  . ' ', array('acceptFlag' => 'k', 'acceptSpace' => true)));
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA  . self::ZENKAKU_KANA . self::ZENKAKU_KIGO . ' ', array('acceptFlag' => 'HK', 'acceptSpace' => true)));
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA  . self::HANKAKU_KANA . self::ZENKAKU_KIGO . ' ', array('acceptFlag' => 'Hk', 'acceptSpace' => true)));
		$this->assertTrue($this->checker->check(self::ZENKAKU_KANA  . self::HANKAKU_KANA . self::ZENKAKU_KIGO . ' ', array('acceptFlag' => 'Kk', 'acceptSpace' => true)));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\KanaException
	 */
	public function testCheckIsNgEnableSpace()
	{
		$this->checker->check(self::ZENKAKU_HIRA . ' ', array('acceptSpace' => false));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\KanaException
	 */
	public function testInvokeMethod()
	{
		$checker = $this->checker;
		$checker->setOption('acceptFlag', 'H');
		$checker('ｚ');
	}

}
