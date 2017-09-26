<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\InvalidValueException;
use Volcanus\Validation\Exception\CheckerException\MaxValueException;

/**
 * MaxValueChecker
 *
 * @author     k.holy74@gmail.com
 */
class MaxValueChecker extends AbstractChecker
{

    public static $forVector = false;

    public function __construct(array $options = array())
    {
        $this->options['max'] = null; // 最大値
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が指定値以下であるか検証します。
     *
     * @param  mixed   検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param  array   検証オプション
     * @return boolean 検証結果
     */
    public function check($value, array $options = array())
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
        if (is_float($stringValue) || is_float($options['max'])) {
            if (floatval($stringValue) > floatval($options['max'])) {
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
