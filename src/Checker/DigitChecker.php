<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\DigitException;

/**
 * DigitChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class DigitChecker extends AbstractChecker
{

    public static $forVector = false;

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が10進数の数字だけで構成されているか検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     */
    public function check($value, array $options = []): bool
    {
        $stringValue = (string)$value;
        if (!ctype_digit($stringValue)) {
            throw new DigitException(
                'The value contains characters other than the numeric.');
        }
        return true;
    }

}
