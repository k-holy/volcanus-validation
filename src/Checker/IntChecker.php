<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\MinValueException;
use Volcanus\Validation\Exception\CheckerException\MaxValueException;

/**
 * IntChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class IntChecker extends AbstractChecker
{

    public static $forVector = false;

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['min'] = null; // 最小値
        $this->options['max'] = null; // 最大値
        $this->options['unsigned'] = false; // +-を有効とするかどうか
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が+-符号および10進数の数字だけで構成されているか検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     * @throws \Exception
     */
    public function check($value, array $options = []): bool
    {
        $options = Util::mergeOptions($this->options, $options);

        $min = $options['min'];
        $max = $options['max'];

        try {
            Util::checkInt($value, $options['unsigned']);
        } catch (\Exception $e) {
            throw $e;
        }

        if (isset($min)) {
            if (!is_int($min)) {
                throw new \InvalidArgumentException(
                    'The min parameter is not integer.');
            }
            if ($value < $min) {
                throw new MinValueException(
                    'The value is less than min parameter.');
            }
        }

        if (isset($max)) {
            if (!is_int($max)) {
                throw new \InvalidArgumentException(
                    'The max parameter is not integer.');
            }
            if ($value > $max) {
                throw new MaxValueException(
                    'The value is greater than max parameter.');
            }
        }
        return true;
    }

}
