<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Exception\CheckerException\EmptyException;
use Volcanus\Validation\Util;

/**
 * NotEmptyChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class NotEmptyChecker extends AbstractChecker
{

    public static bool $forVector = true;

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
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
     * 値が存在するかどうか検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     */
    public function check(mixed $value, array $options = []): bool
    {
        if (is_null($value)) {
            throw new EmptyException(
                'The value is NULL.',
                EmptyException::INVALID_NULL);
        } elseif (is_array($value) || $value instanceof \Countable) {
            if (count($value) === 0) {
                throw new EmptyException(
                    'The array or countable is 0 count.',
                    EmptyException::EMPTY_ARRAY);
            }
        } else {
            $stringValue = (string)$value;
            if (strlen($stringValue) === 0) {
                throw new EmptyException(
                    'The string is 0 length.',
                    EmptyException::EMPTY_STRING);
            }
        }
        return true;
    }

}
