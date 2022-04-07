<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation;

/**
 * Error
 *
 * @property string $type
 * @property array $parameters
 * @property string $message
 *
 * @author k.holy74@gmail.com
 */
class Error
{

    /**
     * @var string このエラーの検証種別
     */
    protected $type = null;

    /**
     * @var array このエラーの検証パラメータ
     */
    protected $parameters = [];

    /**
     * @var string このエラーの表示用メッセージ
     */
    protected $message = null;

    /**
     * コンストラクタ
     *
     * @param string $type このエラーの検証種別
     * @param array $parameters このエラーの検証パラメータ
     */
    public function __construct(string $type, array $parameters = [])
    {
        $this->type = $type;
        $this->parameters = $parameters;
    }

    /**
     * このエラーの検証種別を返します。
     *
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * このエラーの検証パラメータを返します。
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * このエラーの検証パラメータに、指定された検証パラメータが含まれているかどうかを返します。
     *
     * @param mixed $options 検証パラメータ (可変引数)
     * @return bool  検証パラメータがこのエラーの検証パラメータに含まれているかどうか
     */
    public function has($options = []): bool
    {
        return ($options === array_intersect_assoc($this->parameters, $options));
    }

    /**
     * このエラーの表示用メッセージを定義します。
     *
     * @param string $message
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * このエラーの表示用メッセージを返します。
     *
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * __getマジックメソッド
     * $this->foo で $this->getFoo() メソッドが呼ばれます。
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        throw new \RuntimeException(
            sprintf('The property "%s" is not defined.', $name));
    }

    /**
     * __setマジックメソッド
     * $this->foo = $var で $this->setFoo($var) メソッドが呼ばれます。
     *
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __set(string $name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->{$method}($value);
        }
        throw new \RuntimeException(
            sprintf('The property "%s" is not defined.', $name));
    }

    /**
     * __toString()実装
     *
     * @return string
     */
    public function __toString()
    {
        $message = $this->getMessage();
        return (strlen($message) >= 1) ? $message : '';
    }

}
