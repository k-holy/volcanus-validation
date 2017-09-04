<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Checker;

/**
 * AbstractChecker
 *
 * @author     k.holy74@gmail.com
 */
abstract class AbstractChecker implements Checker
{

    public static $forVector = false;

    /**
     * @var array 検証オプション
     */
    protected $options = [];

    /**
     * __construct
     *
     * @param  array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * インスタンスを生成して返します。
     *
     * @param  array $options 検証オプション
     * @return static
     */
    public static function getInstance(array $options = [])
    {
        return new static($options);
    }

    /**
     * __invoke
     *
     * @param  mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param  array $options 検証オプション
     * @return boolean 検証結果
     */
    public function __invoke($value, array $options = [])
    {
        return $this->check($value, $options);
    }

    /**
     * 値が指定した文字だけで構成されているか検証します。
     *
     * @param  mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param  array $options 検証オプション
     * @return boolean 検証結果
     */
    public function check($value, array $options = [])
    {
        $this->options = Util::mergeOptions($this->options, $options);
        return true;
    }

    /**
     * 検証前のガードメソッドを実行します。
     * このメソッドがFALSEを返した場合は検証メソッドを実行しません。
     *
     * @param  mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @return boolean
     */
    public function guard($value)
    {
        if (!isset($value)) {
            return false;
        }
        if (is_array($value) || $value instanceof \Countable) {
            if (count($value) === 0) {
                return false;
            }
        } elseif (is_scalar($value)) {
            if (strlen($value) === 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * 検証オプションを設定します。
     *
     * @param string $name オプション名
     * @param mixed $value オプション値
     * @return $this
     */
    public function setOption($name, $value)
    {
        if (!$this->isEnableOption($name)) {
            throw new \InvalidArgumentException(
                sprintf('The option "%s" is not support.', $name));
        }
        $this->options[$name] = $value;
        return $this;
    }

    /**
     * 検証オプションが有効かどうかを返します。
     *
     * @param string $name オプション名
     * @return boolean
     */
    public function isEnableOption($name)
    {
        return array_key_exists($name, $this->options);
    }

}
