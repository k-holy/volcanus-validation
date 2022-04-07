<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\KanaException;

/**
 * KanaChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class KanaChecker extends AbstractChecker
{

    public static $forVector = false;

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['acceptFlag'] = 'HKk'; // // 許容する文字列
        $this->options['encoding'] = null; // 文字エンコーディング
        $this->options['acceptSign'] = true; // 記号を許可する
        $this->options['acceptSpace'] = false; // 空白を許可する
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が表示可能な文字だけで構成されているか検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     */
    public function check($value, array $options = []): bool
    {
        $options = Util::mergeOptions($this->options, $options);

        $acceptFlag = $options['acceptFlag'];
        $encoding = $options['encoding'];
        $acceptSign = $options['acceptSign'];
        $acceptSpace = $options['acceptSpace'];
        $stringValue = (string)$value;

        $validationEncoding = 'UTF-8';
        if (!isset($encoding)) {
            $encoding = mb_internal_encoding();
        }
        if (strcmp($validationEncoding, $encoding) != 0) {
            $stringValue = mb_convert_encoding($stringValue, $validationEncoding, $encoding);
        }

        $patterns = [];
        // 全角かな＋ー
        if (false !== strpos($acceptFlag, 'H')) {
            $patterns[] = '(\xe3(\x81[\x81-\xbf]|\x82[\x80-\x96]|\x83\xbc))';
        }
        // 全角カナ＋ー
        if (false !== strpos($acceptFlag, 'K')) {
            $patterns[] = '(\xe3(\x82[\xa1-\xbf]|\x83[\x80-\xba]|\x83\xbc))';
        }
        // 半角ｶﾅ＋ｰﾞﾟ
        if (false !== strpos($acceptFlag, 'k')) {
            $patterns[] = '(\xef(\xbd[\xa6-\xbf]|\xbe[\x80-\x9f]))';
        }
        if ($acceptSign) {
            // 全角かな/全角カナ記号（・ヽヾ）
            if (false !== strpos($acceptFlag, 'H') || false !== strpos($acceptFlag, 'K')) {
                $patterns[] = '(\xe3\x83(\xbb|\xbd|\xbe))';
            }
            // 半角ｶﾅ記号（･）
            if (false !== strpos($acceptFlag, 'k')) {
                $patterns[] = '(\xef\xbd\xa5)';
            }
        }
        // 空白を許可
        if ($acceptSpace) {
            $patterns[] = '\040';
        }
        if (!preg_match(sprintf('/\A(%s)+\z/', join('|', $patterns)), $stringValue)) {
            throw new KanaException(
                'The value contains characters that cannot be displayed.');
        }
        return true;
    }

}
