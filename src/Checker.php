<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation;

/**
 * Volcanus_Validation_Checker
 *
 * @author k.holy74@gmail.com
 */
interface Checker
{

    /* 文字長測定モード */
    const LENGTH_BYTES = 'B'; // バイト長
    const LENGTH_CHARS = 'C'; // 文字長
    const LENGTH_WIDTH = 'W'; // 文字幅

    /**
     * 検証メソッドを実行します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     */
    public function check(mixed $value, array $options = []): bool;

    /**
     * 検証前のガードメソッドを実行します。このメソッドがFALSEを返した場合は検証メソッドを実行しません。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @return bool ガード結果
     */
    public function guard(mixed $value): bool;

    /**
     * 検証メソッド
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     */
    public function __invoke(mixed $value, array $options = []): bool;

}
