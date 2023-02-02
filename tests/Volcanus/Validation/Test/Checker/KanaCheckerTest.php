<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\KanaChecker;
use Volcanus\Validation\Exception\CheckerException\KanaException;

/**
 * KanaCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class KanaCheckerTest extends TestCase
{

    const ZENKAKU_HIRA = 'ぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんー';
    const ZENKAKU_KANA = 'ァアィイゥウェエォオカガキギクグケゲコゴサザシジスズセゼソゾタダチヂッツヅテデトドナニヌネノハバパヒビピフブプヘベペホボポマミムメモャヤュユョヨラリルレロヮワヰヱヲンヴヵヶー';
    const ZENKAKU_KIGO = '・ヽヾ';
    const HANKAKU_KANA = 'ｦｧｨｩｪｫｬｭｮｯｰｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜﾝﾞﾟ';
    const HANKAKU_KIGO = '･';

    /** @var  KanaChecker */
    protected KanaChecker $checker;

    public function setUp(): void
    {
        $this->checker = new KanaChecker();
        $this->checker->setOption('encoding', 'UTF-8');
    }

    public function testCheckZenkakuHiragana()
    {
        $this->assertTrue(
            $this->checker->check(self::ZENKAKU_HIRA, ['acceptFlag' => 'H'])
        );
    }

    public function testCheckZenkakuKatakana()
    {
        $this->assertTrue(
            $this->checker->check(self::ZENKAKU_KANA, ['acceptFlag' => 'K'])
        );
    }

    public function testCheckHankakuKatakana()
    {
        $this->assertTrue(
            $this->checker->check(self::HANKAKU_KANA, ['acceptFlag' => 'k'])
        );
    }

    public function testCheckZenkakuHiraganaAndZenkakuKatakana()
    {
        $this->assertTrue(
            $this->checker->check(
                self::ZENKAKU_HIRA . self::ZENKAKU_KANA,
                ['acceptFlag' => 'HK']
            )
        );
    }

    public function testCheckZenkakuHiraganaAndHankakuKatakana()
    {
        $this->assertTrue(
            $this->checker->check(
                self::ZENKAKU_HIRA . self::HANKAKU_KANA,
                ['acceptFlag' => 'Hk']
            )
        );
    }

    public function testCheckZenkakuKatakanaAndHankakuKatakana()
    {
        $this->assertTrue(
            $this->checker->check(
                self::ZENKAKU_KANA . self::HANKAKU_KANA,
                ['acceptFlag' => 'Kk']
            )
        );
    }

    public function testCheckZenkakuHiraganaAndZenkakuKatakanaAndHankakuKatakana()
    {
        $this->assertTrue(
            $this->checker->check(
                self::ZENKAKU_HIRA . self::ZENKAKU_KANA . self::HANKAKU_KANA,
                ['acceptFlag' => 'HKk']
            )
        );
    }

    public function testCheckWithEncoding()
    {
        $this->assertTrue(
            $this->checker->check(
                mb_convert_encoding(self::ZENKAKU_HIRA, 'SJIS', 'UTF-8'),
                ['acceptFlag' => 'H', 'encoding' => 'SJIS']
            )
        );
        $this->assertTrue(
            $this->checker->check(
                mb_convert_encoding(self::ZENKAKU_HIRA, 'EUC-JP', 'UTF-8'),
                ['acceptFlag' => 'H', 'encoding' => 'EUC-JP']
            )
        );
        $this->assertTrue(
            $this->checker->check(
                mb_convert_encoding(self::ZENKAKU_HIRA, 'ISO-2022-JP', 'UTF-8'),
                ['acceptFlag' => 'H', 'encoding' => 'ISO-2022-JP']
            )
        );
    }

    public function testCheckZenkakuWithAcceptSign()
    {
        $this->assertTrue(
            $this->checker->check(
                self::ZENKAKU_HIRA . self::ZENKAKU_KIGO,
                ['acceptFlag' => 'H', 'acceptSign' => true]
            )
        );
        $this->assertTrue(
            $this->checker->check(
                self::ZENKAKU_KANA . self::ZENKAKU_KIGO,
                ['acceptFlag' => 'K', 'acceptSign' => true]
            )
        );
    }

    public function testCheckHankakuWithAcceptSign()
    {
        $this->assertTrue(
            $this->checker->check(
                self::HANKAKU_KANA . self::HANKAKU_KIGO,
                ['acceptFlag' => 'k', 'acceptSign' => true]
            )
        );
    }

    public function testCheckZenkakuAndHankakuWithAcceptSign()
    {
        $this->assertTrue(
            $this->checker->check(
                self::ZENKAKU_HIRA . self::HANKAKU_KANA . self::ZENKAKU_KIGO . self::HANKAKU_KIGO,
                ['acceptFlag' => 'Hk', 'acceptSign' => true]
            )
        );
        $this->assertTrue(
            $this->checker->check(
                self::ZENKAKU_KANA . self::HANKAKU_KANA . self::ZENKAKU_KIGO . self::HANKAKU_KIGO,
                ['acceptFlag' => 'Kk', 'acceptSign' => true]
            )
        );
        $this->assertTrue(
            $this->checker->check(
                self::ZENKAKU_HIRA . self::ZENKAKU_KANA . self::HANKAKU_KANA . self::ZENKAKU_KIGO . self::HANKAKU_KIGO,
                ['acceptFlag' => 'HKk', 'acceptSign' => true]
            )
        );
    }

    public function testCheckWithAcceptSpace()
    {
        $this->assertTrue(
            $this->checker->check(
                self::ZENKAKU_HIRA . ' ',
                ['acceptFlag' => 'H', 'acceptSpace' => true]
            )
        );
    }

    public function testRaiseKanaExceptionWhenCheckIsNgByFormat()
    {
        $this->expectException(KanaException::class);
        $this->checker->check('ｚ');
    }

    public function testRaiseKanaExceptionWhenCheckIsNgByNotAcceptSign()
    {
        $this->expectException(KanaException::class);
        $this->checker->check(
            self::ZENKAKU_HIRA . self::ZENKAKU_KIGO,
            ['acceptFlag' => 'H', 'acceptSign' => false]
        );
    }

    public function testRaiseKanaExceptionWhenCheckIsNgByNotAcceptSpace()
    {
        $this->expectException(KanaException::class);
        $this->checker->check(
            self::ZENKAKU_HIRA . ' ',
            ['acceptFlag' => 'H', 'acceptSpace' => false]
        );
    }

    public function testInvokeMethod()
    {
        $this->expectException(KanaException::class);
        $checker = $this->checker;
        $checker->setOption('acceptFlag', 'H');
        $checker('ｚ');
    }

}
