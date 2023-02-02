<?php
/**
 * Volcanus libraries for PHP 8.1~
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
    protected array $values = [];

    /**
     * @var array 検証エラーオブジェクトの配列
     */
    protected array $errors = [];

    /**
     * コンストラクタ
     *
     * @param object|array|null $values 検証データ
     */
    public function __construct(object|array $values = null)
    {
        $this->init($values);
    }

    /**
     * 検証結果をクリアします。
     *
     * @param object|array|null $values 検証データ
     * @return void
     */
    public function init(object|array $values = null): void
    {
        $this->values = [];
        $this->errors = [];
        if (isset($values)) {
            $this->setValues($values);
        }
    }

    /**
     * 検証エラーをクリアします。
     *
     * @return void
     */
    public function clearErrors(): void
    {
        $this->errors = [];
    }

    /**
     * __get
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->getValue($name);
    }

    /**
     * __set
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, mixed $value): void
    {
        $this->setValue($name, $value);
    }

    /**
     * 検証データをセットします。
     *
     * @param object|array $values 検証データ
     * @return self
     */
    public function setValues(object|array $values): self
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
    public function setValue(string $name, mixed $value): self
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
    public function getValue(string $name): mixed
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
    public function setError(string $name, string $type, Error|array $error = []): self
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
     * @return Error|null 検証エラー
     */
    public function getError(string $name): ?Error
    {
        return $this->errors[$name] ?? null;
    }

    /**
     * \ArrayAccess::offsetExists()
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->values[$offset]);
    }

    /**
     * \ArrayAccess::offsetGet()
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->values[$offset];
    }

    /**
     * \ArrayAccess::offsetSet()
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setValue($offset, $value);
    }

    /**
     * \ArrayAccess::offsetUnset()
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->values[$offset]);
    }

    /**
     * \Countable::count()
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->values);
    }

    /**
     * \IteratorAggregate::getIterator()
     *
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->values);
    }

}
