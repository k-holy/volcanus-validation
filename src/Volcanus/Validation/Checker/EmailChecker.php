<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\EmailException;
use Volcanus\Validation\Exception\CheckerException\MaxLengthException;

/**
 * Volcanus_Validation_Email
 *
 * @author     k.holy74@gmail.com
 */
class EmailChecker extends AbstractChecker
{

	public function __construct(array $options = array())
	{
		$this->options['allowDotEndOfLocalPart'] = false; // ローカルパートの末尾に.を許可するかどうか
		$this->options = Util::mergeOptions($this->options, $options);
	}

	/**
	 * 値がメールアドレスとして妥当か検証します。
	 *
	 * @param  mixed   検証値 (文字列または__toStringメソッド実装オブジェクト)
	 * @param  array   検証オプション
	 * @return boolean 検証結果
	 */
	public function check($value, array $options = array())
	{
		$options = Util::mergeOptions($this->options, $options);

		$allowDotEndOfLocalPart = $options['allowDotEndOfLocalPart'];
		$stringValue = (string)$value;
		// name-addr 形式の場合は add-spec のみ切り出して検証する
		$valid = (bool)(preg_match('/\A[^,<>]+<([^,<>]+)>\s*\z/', $stringValue, $matches))
			? self::validateAddrSpec(trim($matches[1]), $allowDotEndOfLocalPart)
			: self::validateAddrSpec($value, $allowDotEndOfLocalPart);
		if (false === $valid) {
			throw new EmailException(
				'The value is invalid e-mail address.',
				EmailException::INVALID_FORMAT);
		}
		return true;
	}

	/**
	 * メールアドレスを検証します。
	 *
	 * @param  string 検証値
	 * @param  bool   ローカルパートの末尾に.を許可するかどうか
	 * @return boolean 検証結果
	 */
	public static function validateAddrSpec($value, $allowDotEndOfLocalPart=false)
	{
		$stringValue = (string)$value;
		$separatorPosition = strrpos($stringValue, '@');
		$localPart = substr($stringValue, 0, $separatorPosition);
		$domain = substr($stringValue, $separatorPosition + 1);
		if (!self::validateLocalPart($localPart, $allowDotEndOfLocalPart)
			|| !self::validateDomain($domain))
		{
			throw new EmailException(
				'The value is invalid addr-spec.',
				EmailException::INVALID_ADDR_SPEC);
		}

		try {
			Util::checkMaxLength($value, 256, parent::LENGTH_BYTES);
		} catch (MaxLengthException $e) {
			throw new MaxLengthException(
				'A addr-spec of the e-mail address is limited within 256 bytes.');
		} catch (\Exception $e) {
			throw $e;
		}
		return true;
	}

	/**
	 * メールアドレスのローカルパートを検証します。
	 *
	 * @param  string 検証値
	 * @param  bool   ローカルパートの末尾に.を許可するかどうか
	 * @return boolean 検証結果
	 */
	public static function validateLocalPart($value, $allowDotEndOfLocalPart=false)
	{
		$stringValue = (string)$value;
		$pattern = (!$allowDotEndOfLocalPart)
			? '(?:[-!#-\'*+\/-9=?A-Z^-~]+(?:\.[-!#-\'*+\/-9=?A-Z^-~]+)*|"(?:[!#-\[\]-~]|\\\\[\x09 -~])*")'
			: '(?:[-!#-\'*+\/-9=?A-Z^-~]+(?:\.[-!#-\'*+\/-9=?A-Z^-~]+|\.)*|"(?:[!#-\[\]-~]|\\\\[\x09 -~]|\.)*")';
		if ((false !== strpos($stringValue, '..')) ||
			!preg_match(sprintf('/\A%s\z/i', $pattern), $stringValue)) {
			throw new EmailException(
				'The value is invalid local-part.',
				EmailException::INVALID_LOCAL_PART);
		}

		try {
			Util::checkMaxLength($value, 64, parent::LENGTH_BYTES);
		} catch (MaxLengthException $e) {
			throw new MaxLengthException(
				'A local-part of the e-mail address is limited within 64 bytes.');
		} catch (\Exception $e) {
			throw $e;
		}
		return true;
	}

	/**
	 * メールアドレスのドメインを検証します。
	 *
	 * @param  string 検証値
	 * @return boolean 検証結果
	 */
	public static function validateDomain($value)
	{
		$stringValue = (string)$value;
		$pattern = '[-!#-\'*+\/-9=?A-Z^-~]+(?:\.[-!#-\'*+\/-9=?A-Z^-~]+)*';
		if (!preg_match(sprintf('/\A%s\z/i', $pattern), $stringValue)) {
			throw new EmailException(
				'The value is invalid domain.',
				EmailException::INVALID_DOMAIN);
		}

		try {
			Util::checkMaxLength($value, 255, parent::LENGTH_BYTES);
		} catch (MaxLengthException $e) {
			throw new MaxLengthException(
				'A domain of the e-mail address is limited within 255 bytes.');
		} catch (\Exception $e) {
			throw $e;
		}
		return true;
	}

}
