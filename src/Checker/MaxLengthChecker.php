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
 * MaxLengthChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class MaxLengthChecker extends AbstractChecker
{

    public static $forVector = false;

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['length'] = null; // 文字長
        $this->options['encoding'] = null; // 文字エンコーディング
        $this->options['mbLength'] = self::LENGTH_CHARS; // 文字長測定モード 'B':バイト長 'C':文字長 'W':文字幅
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値の文字長が指定値以下であるか検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     * @throws \Exception
     */
    public function check($value, array $options = []): bool
    {
        $options = Util::mergeOptions($this->options, $options);

        if (!isset($options['length'])) {
            throw new \InvalidArgumentException(
                'The parameter "length" is not specified.');
        }

        if (!is_int($options['length'])) {
            throw new \InvalidArgumentException(
                'The parameter "length" is not integer.');
        }

        try {

            if (isset($options['encoding'])) {
                Util::checkEncoding($value, $options['encoding']);
            }

            Util::checkMaxLength(
                $value,
                $options['length'],
                $options['mbLength'] ?? null,
                $options['encoding'] ?? null
            );

        } catch (\Exception $e) {
            throw $e;
        }
        return true;
    }

}
