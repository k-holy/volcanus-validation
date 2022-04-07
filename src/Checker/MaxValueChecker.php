<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\InvalidValueException;
use Volcanus\Validation\Exception\CheckerException\MaxValueException;

/**
 * MaxValueChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class MaxValueChecker extends AbstractChecker
{

    public static $forVector = false;

    /**
     * __construct
     *
     * @param  array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['max'] = null; // 最大値
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が指定値以下であるか検証します。
     *
     * @param  mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param  array $options 検証オプション
     * @return bool 検証結果
     */
    public function check($value, array $options = []):bool
    {
        $options = Util::mergeOptions($this->options, $options);

        if (!isset($options['max'])) {
            throw new \InvalidArgumentException(
                'The parameter "length" is not specified.');
        }

        try {
            Util::checkInt($options['max'], false);
        } catch (\Exception $e) {
            try {
                Util::checkFloat($options['max'], false);
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(
                    'The max parameter contains characters other than the sign and digits and comma.');
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
        if (is_float($options['max'])) {
            if (floatval($stringValue) > $options['max']) {
                throw new MaxValueException(
                    sprintf('The value "%s" is greater than maximum value "%d".', $stringValue, $options['max']));
            }
        } else {
            if (intval($stringValue) > intval($options['max'])) {
                throw new MaxValueException(
                    sprintf('The value "%s" is greater than maximum value "%d".', $stringValue, $options['max']));
            }
        }
        return true;
    }

}
