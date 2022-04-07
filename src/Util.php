<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation;

use Volcanus\Validation\Exception\CheckerException\MaxLengthException;
use Volcanus\Validation\Exception\CheckerException\MinLengthException;
use Volcanus\Validation\Exception\CheckerException\InvalidEncodingException;
use Volcanus\Validation\Exception\CheckerException\IntException;
use Volcanus\Validation\Exception\CheckerException\FloatException;

/**
 * Util
 *
 * @author k.holy74@gmail.com
 */
class Util
{

    /**
     * 配列に対して再帰的に検証処理を実行した結果を返します。
     * エラーを検出した時点で処理を終了します。
     *
     * @param callable $checker チェッカー
     * @param array $value 配列データ
     * @param array $options オプション設定
     * @return bool 検証結果
     */
    public static function recursiveCheck(callable $checker, array $value, array $options = []): bool
    {
        return self::_walk($checker, $value, $options);
    }

    private static function _walk($checker, $value, $options)
    {
        if (isset($value)) {
            if (is_array($value) || $value instanceof \Traversable) {
                foreach ($value as $_value) {
                    if (false === self::_walk($checker, $_value, $options)) {
                        return false;
                    }
                }
            } else {
                return call_user_func($checker, $value, $options);
            }
        }
        return true;
    }

    /**
     * オプション設定をマージして返します。
     * デフォルト設定に存在しないキーを追加設定で検出した場合はInvalidArgumentExceptionをスローします。
     *
     * @param array $defaults デフォルト設定
     * @param array $appends 追加設定
     * @return array オプション設定
     */
    public static function mergeOptions(array $defaults, array $appends): array
    {
        $merged = $defaults;
        if (count($appends) >= 1) {
            foreach (array_keys($appends) as $name) {
                if (!array_key_exists($name, $defaults)) {
                    throw new \InvalidArgumentException(
                        sprintf('The option "%s" is not support.', $name));
                }
            }
            $merged = array_replace_recursive($defaults, $appends);
        }
        return $merged;
    }

    /**
     * 値の文字長が指定値以上であるか検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param int $length 文字長
     * @param string $mbLength 文字長測定モード
     *                 Volcanus\Validation\Checker::LENGTH_BYTES //バイト長
     *                 Volcanus\Validation\Checker::LENGTH_CHARS //文字長
     *                 Volcanus\Validation\Checker::LENGTH_WIDTH //文字幅
     * @param string|null $encoding 文字エンコーディング
     * @return bool 検証結果
     */
    public static function checkMinLength($value, int $length, string $mbLength, string $encoding = null): bool
    {
        $stringValue = (string)$value;
        switch ($mbLength) {
            case Checker::LENGTH_CHARS:
                $characterLength = (isset($encoding))
                    ? mb_strlen($stringValue, $encoding)
                    : mb_strlen($stringValue);
                $mbLengthType = 'characters';
                break;
            case Checker::LENGTH_WIDTH:
                $characterLength = (isset($encoding))
                    ? mb_strwidth($stringValue, $encoding)
                    : mb_strwidth($stringValue);
                $mbLengthType = 'width';
                break;
            case Checker::LENGTH_BYTES:
            default:
                $characterLength = strlen($stringValue);
                $mbLengthType = 'bytes';
                break;
        }
        if (!isset($characterLength) || !isset($mbLengthType)) {
            throw new \InvalidArgumentException(
                sprintf('The mbLength parameter "%s" is not valid.', $mbLength));
        }
        if ($characterLength < $length) {
            throw new MinLengthException(
                sprintf('The %s of the characters "%d" is less than specified value "%d".',
                    $mbLengthType, $characterLength, $length));
        }
        return true;
    }

    /**
     * 値の文字長が指定値以下であるか検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param int $length 文字長
     * @param string $mbLength 文字長測定モード
     *                 Volcanus\Validation\Checker::LENGTH_BYTES //バイト長
     *                 Volcanus\Validation\Checker::LENGTH_CHARS //文字長
     *                 Volcanus\Validation\Checker::LENGTH_WIDTH //文字幅
     * @param string|null $encoding 文字エンコーディング
     * @return bool 検証結果
     */
    public static function checkMaxLength($value, int $length, string $mbLength, string $encoding = null): bool
    {
        $stringValue = (string)$value;
        switch ($mbLength) {
            case Checker::LENGTH_WIDTH:
                $characterLength = (isset($encoding))
                    ? mb_strwidth($stringValue, $encoding)
                    : mb_strwidth($stringValue);
                $mbLengthType = 'width';
                break;
            case Checker::LENGTH_CHARS:
                $characterLength = (isset($encoding))
                    ? mb_strlen($stringValue, $encoding)
                    : mb_strlen($stringValue);
                $mbLengthType = 'characters';
                break;
            case Checker::LENGTH_BYTES:
            default:
                $characterLength = strlen($stringValue);
                $mbLengthType = 'bytes';
                break;
        }
        if (!isset($characterLength) || !isset($mbLengthType)) {
            throw new \InvalidArgumentException(
                sprintf('The mbLength parameter "%s" is not valid.', $mbLength));
        }
        if ($characterLength > $length) {
            throw new MaxLengthException(
                sprintf('The %s of the characters "%d" is less than specified value "%d".',
                    $mbLengthType, $characterLength, $length));
        }
        return true;
    }

    /**
     * 値が+-符号および10進数の数字だけで構成されているか検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param bool $unsigned +-を無効とするかどうか
     * @return bool 検証結果
     */
    public static function checkInt($value, bool $unsigned = false): bool
    {
        if (is_string($value)) {
            $stringValue = $value;
        } elseif (is_int($value)) {
            $stringValue = sprintf('%d', $value);
        } elseif (is_float($value)) {
            $stringValue = sprintf('%.0f', $value);
        } else {
            $stringValue = (string)$value;
        }
        if ($unsigned) {
            if (!preg_match('/\A(?:0|[1-9][0-9]*)\z/', $stringValue)) {
                throw new IntException(
                    'The value contains characters other than digits.');
            }
        } else {
            if (!preg_match('/\A[+-]?0*(?:0|[1-9][0-9]*)\z/', $stringValue)) {
                throw new IntException(
                    'The value contains characters other than sign and digits.');
            }
        }
        return true;
    }

    /**
     * 値が+-符号および10進数の数字と.だけで構成されているか検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param bool $unsigned +-を無効とするかどうか
     * @return bool 検証結果
     */
    public static function checkFloat($value, bool $unsigned = false): bool
    {
        $stringValue = (string)$value;
        if ($unsigned) {
            if (!preg_match('/\A\d+(?:\.\d+)?\z/', $stringValue)) {
                throw new FloatException(
                    'The value contains characters other than digits and dot.');
            }
        } else {
            if (!preg_match('/\A[+-]?\d+(?:\.\d+)?\z/', $stringValue)) {
                throw new FloatException(
                    'The value contains characters other than sign and digits and dot.');
            }
        }
        return true;
    }

    /**
     * 値が指定された文字エンコーディングとして妥当かどうかを再帰的に検証します。
     *
     * @param mixed $value 検証したい文字列または配列
     * @param string $encoding 文字エンコーディング
     * @return bool 検証結果
     */
    public static function checkEncoding($value, string $encoding): bool
    {
        if (false === self::_checkEncoding($value, $encoding)) {
            throw new InvalidEncodingException(
                'The value is invalid encoding.');
        }
        return true;
    }

    private static function _checkEncoding($value, $encoding): bool
    {
        if (isset($value)) {
            if (is_array($value) || $value instanceof \Traversable) {
                foreach ($value as $_value) {
                    if (false === self::_checkEncoding($_value, $encoding)) {
                        return false;
                    }
                }
            } else {
                return mb_check_encoding($value, $encoding);
            }
        }
        return true;
    }

    /**
     * 配列に対して再帰的に処理を実行した結果を返します。
     *
     * @param callable $func 処理
     * @param array $value 配列
     * @return array 処理後の配列
     */
    public static function map(callable $func, array $value): array
    {
        return self::_map($func, $value);
    }

    private static function _map($func, $value): array
    {
        $return = [];
        foreach ($value as $key => $val) {
            if (is_array($val)) {
                $return[$key] = self::_map($func, $val);
            } else {
                $return[$key] = call_user_func($func, $val);
            }
        }
        return $return;
    }

}
