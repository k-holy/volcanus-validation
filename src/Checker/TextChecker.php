<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;

/**
 * TextChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class TextChecker extends AbstractChecker
{

    public static $forVector = false;

    /**
     * __construct
     *
     * @param  array $options 検証オプション
     */
    public function __construct(array $options = array())
    {
        $this->options['minLength'] = null; // 最小文字長
        $this->options['maxLength'] = null; // 最大文字長
        $this->options['encoding'] = null; // 文字エンコーディング
        $this->options['mbLength'] = self::LENGTH_CHARS; // 文字長測定モード 'B':バイト長 'C':文字長 'W':文字幅
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が表示可能な文字だけで構成されているか検証します。
     *
     * @param  mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param  array $options 検証オプション
     * @return bool 検証結果
     * @throws \Exception
     */
    public function check($value, array $options = array())
    {
        $options = Util::mergeOptions($this->options, $options);

        try {

            if (isset($options['encoding'])) {
                Util::checkEncoding($value, $options['encoding']);
            }

            if (isset($options['minLength'])) {
                Util::checkMinLength($value,
                    $options['minLength'],
                    (isset($options['mbLength'])) ? $options['mbLength'] : null,
                    (isset($options['encoding'])) ? $options['encoding'] : null
                );
            }

            if (isset($options['maxLength'])) {
                Util::checkMaxLength($value,
                    $options['maxLength'],
                    (isset($options['mbLength'])) ? $options['mbLength'] : null,
                    (isset($options['encoding'])) ? $options['encoding'] : null
                );
            }

        } catch (\Exception $e) {
            throw $e;
        }
        return true;
    }

}
