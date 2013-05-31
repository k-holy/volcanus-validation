<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation;

use Volcanus\Validation\Util;
use Volcanus\Validation\Checker;
use Volcanus\Validation\Exception\CheckerException;

/**
 * Context
 *
 * @author     k.holy74@gmail.com
 */
class Context
{

	/* @var array チェッカーの配列 */
	protected $checkers;

	/* @var object 検証結果オブジェクト */
	protected $result;

	/* @var callable メッセージ生成処理のコールバック **/
	protected $messageProcessor;

	/* @var array 検証オプションのグローバル設定 */
	protected $options;

	/**
	 * コンストラクタ
	 *
	 * @param mixed 検証データ
	 * @param array チェッカーの配列
	 */
	public function __construct($values = null, $checkers = null, $options = array())
	{
		$this->options['encoding'   ] = null; // 入力エンコーディング
		$this->options['acceptArray'] = false; // 配列の検証値を処理するかどうか
		$this->initResult($values);
		$this->initChecker($checkers);
		$this->options = Util::mergeOptions($this->options, $options);
	}

	/**
	 * インスタンスを生成して返します。
	 *
	 * @return $this
	 */
	public static function getInstance($values = null)
	{
		return new self($values);
	}

	/**
	 * 検証結果オブジェクトを初期化します。
	 *
	 * @param mixed 検証データ
	 */
	public function initResult($values = null)
	{
		$this->result = new \Volcanus\Validation\Result($values);
	}

	/**
	 * チェッカーを初期化します。
	 *
	 * @param array チェッカーの配列
	 * @return $this
	 */
	public function initChecker($checkers = null)
	{
		$this->checkers = array();
		if (isset($checkers) && is_array($checkers)) {
			foreach ($checkers as $type => $checker) {
				$this->registerChecker($type, $checker);
			}
		}
		return $this;
	}

	/**
	 * 検証結果オブジェクトを返します。
	 *
	 * @return object Volcanus\Validation\Result
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * チェッカーを登録します。
	 *
	 * @param string 検証種別
	 * @param callable チェッカー
	 */
	public function registerChecker($type, $checker)
	{
		if (!is_callable($checker)) {
			throw new \InvalidArgumentException(
				sprintf('The checker "%s" is not callable.', $type));
		}
		$this->checkers[$type] = $checker;
		return $this;
	}

	/**
	 * チェッカーを解放します。
	 *
	 * @param string 検証種別
	 */
	public function unregisterChecker($type)
	{
		unset($this->checkers[$type]);
	}

	/**
	 * 検証種別を指定してチェッカーを返します。
	 * セット済ではない場合、デフォルトの検証オブジェクトを返します。
	 *
	 * @param  string  検証種別
	 * @return callable チェッカー
	 */
	public function getChecker($type)
	{
		if (!isset($this->checkers[$type])) {
			try {
				$defaultChecker = $this->getDefaultChecker($type);
			} catch (\RuntimeException $e) {
				throw $e;
			}
			$this->checkers[$type] = $defaultChecker;
		}
		return $this->checkers[$type];
	}

	/**
	 * 検証種別を指定してデフォルトの検証オブジェクトを返します。
	 *
	 * @param  string  検証種別
	 * @return object  Volcanus\Validation\Checker
	 */
	public function getDefaultChecker($type)
	{
		$class = 'Volcanus\Validation\Checker\\' . ucfirst($type) . 'Checker';
		if (!class_exists($class, true)) {
			throw new \RuntimeException(
				sprintf('The checker type "%s" is not defined.', $type));
		}
		return new $class();
	}

	/**
	 * メッセージ生成処理をセットします。
	 *
	 * @param  callable メッセージ生成処理
	 */
	public function setMessageProcessor($messageProcessor)
	{
		if (!is_callable($messageProcessor)) {
			throw new \InvalidArgumentException(
				sprintf('The messageProcessor is not callable. type:%s',
				is_object($messageProcessor)
					? get_class($messageProcessor)
					: gettype($messageProcessor)
				)
			);
		}
		$this->messageProcessor = $messageProcessor;
		return $this;
	}

	/**
	 * 検証データの項目名および検証種別を指定して検証処理を実行し、結果を返します。
	 * 対象項目がすでにエラーを検出されている場合はそのままFALSEを返します。
	 * 検証処理で検証例外が発生した場合は検証結果オブジェクトにエラー情報をセットしてFALSEを返します。
	 *
	 * @param string 検証データの項目名
	 * @param string 検証種別
	 * @return boolean 検証結果
	 */
	public function check($name, $type, $options = array())
	{
		if ($this->isError($name)) {
			return false;
		}
		$checker = $this->checker($type);
		if (!is_callable($checker)) {
			throw new \RuntimeException(
				sprintf('The checker type "%s" is not defined.', $type));
		}
		$value = $this->result->getValue($name);
		if ($checker instanceof Checker) {
			if (false === $checker->guard($value)) {
				return;
			}
		}
		if (strcmp('compare', $type) === 0) {
			if (isset($options['compareTo'])) {
				$compareValue = $this->result->getValue($options['compareTo']);
				if (isset($compareValue)) {
					$options['compareTo'] = $compareValue;
				}
			}
		}
		$valid = null;
		if ((is_array($value) || $value instanceof \Traversable)) {
			if (!isset($options['acceptArray']) || !$options['acceptArray']) {
				throw new \InvalidArgumentException(
					sprintf('The value is array or Traversable. type:%s',
					is_object($value)
						? get_class($value)
						: gettype($value)
					)
				);
			}
			try {
				$valid = Util::recursiveCheck($checker, $value, $options);
			} catch (CheckerException $e) {
				$valid = false;
			}
		} else {
			try {
				$valid = call_user_func($checker, $value, $options);
			} catch (CheckerException $e) {
				$valid = false;
			}
		}
		if (false === $valid) {
			$this->setError($name, $type, $options);
		}
		return $valid;
	}

	/**
	 * 検証処理を実行し、エラーあったかどうかを返します。
	 *
	 * @return boolean
	 */
	public function isValid()
	{
		return !($this->result->hasError());
	}

	/**
	 * 指定した項目名の検証エラーを設定します。
	 *
	 * @param string 検証データの項目名
	 * @param  string  エラーの検証種別
	 * @param  array   エラーのパラメータ
	 */
	public function setError($name, $type, $options = array())
	{
		$this->result->setError($name, $type, $options);
		if (isset($this->messageProcessor)) {
			$message = call_user_func($this->messageProcessor, $name, $type, $options);
			$this->result->setMessage($name, $message);
		}
		return $this;
	}

	/**
	 * 指定した項目名の検証エラーをクリアします。
	 *
	 * @param string 検証データの項目名
	 */
	public function unsetError($name)
	{
		$this->result->unsetError($name);
		return $this;
	}

	/**
	 * 検証結果に、指定した項目名の検証エラーが含まれているかどうかを返します。
	 *
	 * @param string 検証データの項目名
	 * @param  string  エラーの検証種別
	 * @param  array   エラーのパラメータ
	 * @return bool エラーが含まれているかどうか
	 */
	public function isError($name, $type = null, $options = array())
	{
		return $this->result->hasError($name, $type, $options);
	}

	/**
	 * 指定した項目名の検証エラーメッセージを返します。
	 *
	 * @param string 検証データの項目名
	 * @return string 検証エラーメッセージ
	 */
	public function getMessage($name)
	{
		$error = $this->result->getError($name);
		if (!is_null($error)) {
			return $error->getMessage();
		}
	}

	/**
	 * すべての検証エラーメッセージを返します。
	 *
	 * @return array 検証エラーメッセージの配列
	 */
	public function getMessages()
	{
		$messages = array();
		foreach ($this->result->getErrors() as $name => $error) {
			$messages[$name] = $error->getMessage();
		}
		return $messages;
	}

	/**
	 * __getマジックメソッド
	 * $this->foo で $this->getFoo() メソッドが呼ばれます。
	 *
	 * @param string
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
	 * @param string
	 * @param mixed
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
	 * __callマジックメソッド
	 * $this->foo($var) で $this->getFoo($var) メソッドが呼ばれます。
	 *
	 * @param string
	 * @param array
	 */
	public function __call($name, $args)
	{
		$method = 'get' . ucfirst($name);
		if (method_exists($this, $method)) {
			return call_user_func_array(array($this, $method), $args);
		}
		throw new \RuntimeException(
			sprintf('The method "%s" is not defined.', $name));
	}

}
