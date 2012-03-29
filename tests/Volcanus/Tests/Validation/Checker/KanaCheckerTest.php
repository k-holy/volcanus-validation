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

	const ZENKAKU_HIRA = 'ぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんー';
	const ZENKAKU_KANA = 'ァアィイゥウェエォオカガキギクグケゲコゴサザシジスズセゼソゾタダチヂッツヅテデトドナニヌネノハバパヒビピフブプヘベペホボポマミムメモャヤュユョヨラリルレロヮワヰヱヲンヴヵヶー';
	const ZENKAKU_KIGO = '・ヽヾ';
	const HANKAKU_KANA = 'ｦｧｨｩｪｫｬｭｮｯｰｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜﾝﾞﾟ';
	const HANKAKU_KIGO = '･';

	protected $checker;
	protected $internalEncoding;

	public function setUp()
	{
		$this->checker = new KanaChecker();
		$this->checker->setOption('encoding', 'UTF-8');
	}

	public function testCheckZenkakuHiragana()
	{
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA, array('acceptFlag' => 'H')));
	}

	public function testCheckZenkakuKatakana()
	{
		$this->assertTrue($this->checker->check(self::ZENKAKU_KANA,
			array('acceptFlag' => 'K')));
	}

	public function testCheckHankakuKatakana()
	{
		$this->assertTrue($this->checker->check(self::HANKAKU_KANA,
			array('acceptFlag' => 'k')));
	}

	public function testCheckZenkakuHiraganaAndZenkakuKatakana()
	{
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA . self::ZENKAKU_KANA,
			array('acceptFlag' => 'HK')));
	}

	public function testCheckZenkakuHiraganaAndHankakuKatakana()
	{
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA . self::HANKAKU_KANA,
			array('acceptFlag' => 'Hk')));
	}

	public function testCheckZenkakuKatakanaAndHankakuKatakana()
	{
		$this->assertTrue($this->checker->check(self::ZENKAKU_KANA . self::HANKAKU_KANA,
			array('acceptFlag' => 'Kk')));
	}

	public function testCheckZenkakuHiraganaAndZenkakuKatakanaAndHankakuKatakana()
	{
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA . self::ZENKAKU_KANA . self::HANKAKU_KANA,
			array('acceptFlag' => 'HKk')));
	}

	public function testCheckWithEncoding()
	{
		$this->assertTrue($this->checker->check(mb_convert_encoding(self::ZENKAKU_HIRA, 'SJIS', 'UTF-8'),
			array('acceptFlag' => 'H', 'encoding' => 'SJIS')));
		$this->assertTrue($this->checker->check(mb_convert_encoding(self::ZENKAKU_HIRA, 'EUC-JP', 'UTF-8'),
			array('acceptFlag' => 'H', 'encoding' => 'EUC-JP')));
		$this->assertTrue($this->checker->check(mb_convert_encoding(self::ZENKAKU_HIRA, 'ISO-2022-JP', 'UTF-8'),
			array('acceptFlag' => 'H', 'encoding' => 'ISO-2022-JP')));
	}

	public function testCheckZenkakuWithAcceptSign()
	{
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA . self::ZENKAKU_KIGO,
			array('acceptFlag' => 'H', 'acceptSign' => true)));
		$this->assertTrue($this->checker->check(self::ZENKAKU_KANA . self::ZENKAKU_KIGO,
			array('acceptFlag' => 'K', 'acceptSign' => true)));
	}

	public function testCheckHankakuWithAcceptSign()
	{
		$this->assertTrue($this->checker->check(self::HANKAKU_KANA . self::HANKAKU_KIGO,
			array('acceptFlag' => 'k', 'acceptSign' => true)));
	}

	public function testCheckZenkakuAndHankakuWithAcceptSign()
	{
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA . self::HANKAKU_KANA . self::ZENKAKU_KIGO . self::HANKAKU_KIGO,
			array('acceptFlag' => 'Hk', 'acceptSign' => true)));
		$this->assertTrue($this->checker->check(self::ZENKAKU_KANA . self::HANKAKU_KANA . self::ZENKAKU_KIGO . self::HANKAKU_KIGO,
			array('acceptFlag' => 'Kk', 'acceptSign' => true)));
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA . self::ZENKAKU_KANA . self::HANKAKU_KANA . self::ZENKAKU_KIGO . self::HANKAKU_KIGO,
			array('acceptFlag' => 'HKk', 'acceptSign' => true)));
	}

	public function testCheckWithAcceptSpace()
	{
		$this->assertTrue($this->checker->check(self::ZENKAKU_HIRA . ' ',
			array('acceptFlag' => 'H', 'acceptSpace' => true)));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\KanaException
	 */
	public function testRaiseKanaExceptionWhenCheckIsNgByFormat()
	{
		$this->checker->check('ｚ');
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\KanaException
	 */
	public function testRaiseKanaExceptionWhenCheckIsNgByNotAcceptSign()
	{
		$this->checker->check(self::ZENKAKU_HIRA . self::ZENKAKU_KIGO,
			array('acceptFlag' => 'H', 'acceptSign' => false));
	}

	/**
	 * @expectedException Volcanus\Validation\Exception\CheckerException\KanaException
	 */
	public function testRaiseKanaExceptionWhenCheckIsNgByNotAcceptSpace()
	{
		$this->checker->check(self::ZENKAKU_HIRA . ' ',
			array('acceptFlag' => 'H', 'acceptSpace' => false));
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
