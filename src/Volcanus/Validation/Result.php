<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation;

use Volcanus\Validation\Error;

/**
 * Result
 *
 * @author     k.holy74@gmail.com
 */
class Result implements \ArrayAccess, \IteratorAggregate, \Countable
{

	/**
	 * @var array 検証データの配列
	 */
	protected $values = array();

	/**
	 * @var array 検証エラーオブジェクトの配列
	 */
	protected $errors = array();

	/**
	 * コンストラクタ
	 *
	 * @param array|object 検証データ
	 */
	public function __construct($values = null)
	{
		$this->init($values);
	}

	/**
	 * 検証結果をクリアします。
	 */
	public function init($values = null)
	{
		$this->values = array();
		$this->errors = array();
		if (isset($values)) {
			$this->setValues($values);
		}
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
	 * @param array|object 検証データ
	 * @return $this
	 */
	public function setValues($values)
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
	 * @param string 項目名
	 * @param mixed 検証データ値
	 * @return $this
	 */
	public function setValue($name, $value)
	{
		$this->values[$name] = $value;
		return $this;
	}

	/**
	 * このオブジェクトの値を返します。
	 *
	 * @return array 
	 */
	public function getValues()
	{
		return $this->values;
	}

	/**
	 * 指定された項目の値を返します。
	 *
	 * @param string 項目名
	 * @return mixed 検証データ値
	 */
	public function getValue($name)
	{
		return (isset($this->values[$name])) ? $this->values[$name] : null;
	}

	/**
	 * 指定された項目にエラーをセットします。
	 *
	 * @param string 項目名
	 * @param string 検証種別
	 * @param mixed Array or Volcanus\Validation\Error
	 * @return $this
	 */
	public function setError($name, $type, $error = array())
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
	 * @param string 項目名
	 * @param string エラーメッセージ
	 * @return $this
	 */
	public function setMessage($name, $message)
	{
		if (!isset($this->errors[$name])) {
			throw new \InvalidArgumentException(
				sprintf('The value of "%s" is not defined.', $name));
		}
		$this->errors[$name]->setMessage($message);
	}

	/**
	 * 指定された項目にセットされたエラーをクリアします。
	 *
	 * @param string 項目名
	 * @return $this
	 */
	public function unsetError($name)
	{
		if (array_key_exists($name, $this->errors)) {
			unset($this->errors[$name]);
		}
		return $this;
	}

	/**
	 * 指定された検証種別および検証内容のエラー情報があるかどうかを返します。
	 *
	 * @param string 項目名
	 * @param string 検証種別
	 * @param array  検証内容
	 * @return bool  比較値がこの検証種別のエラーに含まれているかどうか
	 */
	public function hasError($name = null, $type = null, $options = array())
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
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * 指定された項目名の検証エラーを返します
	 *
	 * @param string 項目名
	 * @return mixed 検証エラー
	 */
	public function getError($name)
	{
		return (isset($this->errors[$name])) ? $this->errors[$name] : null;
	}

	/**
	 * ArrayAccess::offsetExists()実装
	 *
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->values[$offset]);
	}

	/**
	 * ArrayAccess::offsetGet()実装
	 *
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->values[$offset];
	}

	/**
	 * ArrayAccess::offsetSet()実装
	 *
	 */
	public function offsetSet($offset, $value)
	{
		$this->setValue($offset, $value);
	}

	/**
	 * ArrayAccess::offsetUnset()実装
	 *
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
	public function count() {
		return count($this->values);
	}

	/**
	 * IteratorAggregate::getIterator()実装
	 *
	 * @return object ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->values);
	}

}
