<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\MinValueException;
use Volcanus\Validation\Exception\CheckerException\MaxValueException;

/**
 * ArraySizeChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class ArraySizeChecker extends AbstractChecker
{

    public static bool $forVector = true;

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['minSize'] = null; // 最小値
        $this->options['maxSize'] = null; // 最大値
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 検証前のガードメソッドを実行します。このメソッドがFALSEを返した場合は検証メソッドを実行しません。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @return bool
     */
    public function guard(mixed $value): bool
    {
        return true;
    }

    /**
     * 値が指定された範囲の要素数を持つ配列かどうかを検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     */
    public function check(mixed $value, array $options = []): bool
    {
        $options = Util::mergeOptions($this->options, $options);

        if (false === is_array($value) && false === ($value instanceof \Countable)) {
            throw new \InvalidArgumentException(
                'The value is not an Array and not Countable.');
        }

        $minSize = $options['minSize'];
        $maxSize = $options['maxSize'];

        $size = count($value);
        if (isset($minSize)) {
            if (!is_int($minSize)) {
                throw new \InvalidArgumentException(
                    'The minSize parameter is not integer.');
            }
            if ($size < $minSize) {
                throw new MinValueException(
                    'The array Size is less than minimum value.');
            }
        }
        if (isset($maxSize)) {
            if (!is_int($maxSize)) {
                throw new \InvalidArgumentException(
                    'The maxSize parameter is not integer.');
            }
            if ($size > $maxSize) {
                throw new MaxValueException(
                    'The array length is greater than maximum value.');
            }
        }
        return true;
    }

}
