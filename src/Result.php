<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation;

/**
 * Result
 *
 * @author k.holy74@gmail.com
 */
class Result implements \ArrayAccess, \IteratorAggregate, \Countable
{

    /**
     * @var array 検証データの配列
     */
    protected $values = [];

    /**
     * @var array 検証エラーオブジェクトの配列
     */
    protected $errors = [];

    /**
     * コンストラクタ
     *
     * @param array|object $values 検証データ
     */
    public function __construct($values = null)
    {
        $this->init($values);
    }

    /**
     * 検証結果をクリアします。
     *
     * @param array|object $values 検証データ
     */
    public function init($values = null)
    {
        $this->values = [];
        $this->errors = [];
        if (isset($values)) {
            $this->setValues($values);
        }
    }

    /**
     * 検証エラーをクリアします。
     */
    public function clearErrors()
    {
        $this->errors = [];
    }

    public function __get($name)
    {
        return $this->getValue($name);
    }

    public function __set($name, $value)
    {
        return $this->setValue($name, $value);
    }

    /**
     * 検証データをセットします。
     *
     * @param array|object $values 検証データ
     * @return self
     */
    public function setValues($values): self
    {
        if (is_array($values) || ($values instanceof \Traversable)) {
            foreach ($values as $name => $value) {
                $this->setValue($name, $value);
            }
        } elseif (is_object($values)) {
            foreach (array_keys(get_object_vars($values)) as $name) {
                $this->setValue($name, $values->{$name});
            }
        } else {
            throw new \InvalidArgumentException(
                sprintf('The values is invalid type. %s', gettype($values)));
        }
        return $this;
    }

    /**
     * 検証データに値を追加します。
     *
     * @param string $name 項目名
     * @param mixed $value 検証データ値
     * @return self
     */
    public function setValue(string $name, $value): self
    {
        $this->values[$name] = $value;
        return $this;
    }

    /**
     * このオブジェクトの値を返します。
     *
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * 指定された項目の値を返します。
     *
     * @param string $name 項目名
     * @return mixed 検証データ値
     */
    public function getValue(string $name)
    {
        return $this->values[$name] ?? null;
    }

    /**
     * 指定された項目にエラーをセットします。
     *
     * @param string $name 項目名
     * @param string $type 検証種別
     * @param array|Error $error
     * @return self
     */
    public function setError(string $name, string $type, $error = []): self
    {
        if (false === ($error instanceof Error)) {
            $error = new Error($type, $error);
        }
        $this->errors[$name] = $error;
        return $this;
    }

    /**
     * 指定された項目にエラーメッセージをセットします。
     *
     * @param string $name 項目名
     * @param string $message エラーメッセージ
     * @return self
     */
    public function setMessage(string $name, string $message): self
    {
        if (!isset($this->errors[$name])) {
            throw new \InvalidArgumentException(
                sprintf('The value of "%s" is not defined.', $name));
        }
        $this->errors[$name]->setMessage($message);
        return $this;
    }

    /**
     * 指定された項目にセットされたエラーをクリアします。
     *
     * @param string $name 項目名
     * @return self
     */
    public function unsetError(string $name): self
    {
        if (array_key_exists($name, $this->errors)) {
            unset($this->errors[$name]);
        }
        return $this;
    }

    /**
     * 指定された検証種別および検証内容のエラー情報があるかどうかを返します。
     *
     * @param string|null $name 項目名
     * @param string|null $type 検証種別
     * @param array $options 検証内容
     * @return bool  比較値がこの検証種別のエラーに含まれているかどうか
     */
    public function hasError(string $name = null, string $type = null, array $options = []): bool
    {
        if (!isset($name)) {
            return (count($this->errors) >= 1);
        }
        $error = $this->getError($name);
        if (!isset($type)) {
            return (isset($error));
        }
        return (isset($error) && strcmp($type, $error->getType()) === 0 &&
            $error->has($options));
    }

    /**
     * 検証エラーの配列を返します。
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * 指定された項目名の検証エラーを返します
     *
     * @param string $name 項目名
     * @return mixed 検証エラー
     */
    public function getError(string $name)
    {
        return $this->errors[$name] ?? null;
    }

    /**
     * ArrayAccess::offsetExists()実装
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->values[$offset]);
    }

    /**
     * ArrayAccess::offsetGet()実装
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->values[$offset];
    }

    /**
     * ArrayAccess::offsetSet()実装
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->setValue($offset, $value);
    }

    /**
     * ArrayAccess::offsetUnset()実装
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }

    /**
     * Countable::count()実装
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->values);
    }

    /**
     * IteratorAggregate::getIterator()実装
     *
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->values);
    }

}
