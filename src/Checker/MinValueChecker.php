<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\InvalidValueException;
use Volcanus\Validation\Exception\CheckerException\MinValueException;

/**
 * MinValueChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class MinValueChecker extends AbstractChecker
{

    public static bool $forVector = false;

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['min'] = null; // 最小値
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が指定値以上であるか検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     */
    public function check(mixed $value, array $options = []): bool
    {
        $options = Util::mergeOptions($this->options, $options);

        if (!isset($options['min'])) {
            throw new \InvalidArgumentException(
                'The parameter "length" is not specified.');
        }

        try {
            Util::checkInt($options['min'], false);
        } catch (\Exception $e) {
            try {
                Util::checkFloat($options['min'], false);
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(
                    'The min parameter contains characters other than the sign and digits and comma.');
            }
        }

        try {
            Util::checkInt($value, false);
        } catch (\Exception $e) {
            try {
                Util::checkFloat($value, false);
            } catch (\Exception $e) {
                throw new InvalidValueException(
                    'The value contains characters other than the sign and digits and comma.');
            }
        }

        $stringValue = (string)$value;
        if (is_float($options['min'])) {
            if (floatval($stringValue) < $options['min']) {
                throw new MinValueException(
                    sprintf('The value "%s" is less than minimum value "%d".', $stringValue, $options['min']));
            }
        } else {
            if (intval($stringValue) < intval($options['min'])) {
                throw new MinValueException(
                    sprintf('The value "%s" is less than minimum value "%d".', $stringValue, $options['min']));
            }
        }
        return true;
    }

}
