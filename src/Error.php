<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation;

/**
 * Error
 *
 * @property $type
 * @property $parameters
 * @property $message
 *
 * @author     k.holy74@gmail.com
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
	protected $parameters = array();

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
	public function __construct($type, array $parameters = array())
	{
		$this->type = $type;
		$this->parameters = $parameters;
	}

	/**
	 * このエラーの検証種別を返します。
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * このエラーの検証パラメータを返します。
	 *
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * このエラーの検証パラメータに、指定された検証パラメータが含まれているかどうかを返します。
	 *
	 * @param mixed $options 検証パラメータ (可変引数)
	 * @return bool  検証パラメータがこのエラーの検証パラメータに含まれているかどうか
	 */
	public function has($options = array())
	{
		return ($options === array_intersect_assoc($this->parameters, $options));
	}

    /**
     * このエラーの表示用メッセージを定義します。
     *
     * @param string $message
     * @return $this
     */
	public function setMessage($message)
	{
		if (!is_string($message)) {
			throw new \InvalidArgumentException(
				'The message is not string.');
		}
		$this->message = $message;
		return $this;
	}

	/**
	 * このエラーの表示用メッセージを返します。
	 *
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * __getマジックメソッド
	 * $this->foo で $this->getFoo() メソッドが呼ばれます。
	 *
	 * @param string $name
	 */
	public function __get($name)
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
	 */
	public function __set($name, $value)
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
