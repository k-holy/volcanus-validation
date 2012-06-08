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
use Volcanus\Validation\Exception\CheckerException\InvalidValueException;
use Volcanus\Validation\Exception\CheckerException\MinValueException;

/**
 * MinValueChecker
 *
 * @author     k.holy74@gmail.com
 */
class MinValueChecker extends AbstractChecker
{

	public function __construct(array $options = array())
	{
		$this->options['min'] = null; // 最小値
		$this->options = Util::mergeOptions($this->options, $options);
	}

	/**
	 * 値が指定値以上であるか検証します。
	 *
	 * @param  mixed   検証値 (文字列または__toStringメソッド実装オブジェクト)
	 * @param  array   検証オプション
	 * @return boolean 検証結果
	 */
	public function check($value, array $options = array())
	{
		$options = Util::mergeOptions($this->options, $options);

		if (!isset($options['min'])) {
			throw new \InvalidArgumentException(
				'The parameter "length" is not specified.');
		}

		try {
			Util::checkInt($options['min'], false);
		} catch (\Exception $e) {
			try {
				Util::checkFloat($options['min'], false);
			} catch (\Exception $e) {
				throw new \InvalidArgumentException(
					'The min parameter contains characters other than the sign and digits and comma.');
			}
		}

		try {
			Util::checkInt($value, false);
		} catch (\Exception $e) {
			try {
				Util::checkFloat($value, false);
			} catch (\Exception $e) {
				throw new InvalidValueException(
					'The value contains characters other than the sign and digits and comma.');
			}
		}

		$stringValue = (string)$value;
		if (is_float($stringValue) || is_float($options['min'])) {
			if (floatval($stringValue) < floatval($options['min'])) {
				throw new MinValueException(
					sprintf('The value "%s" is less than minimum value "%d".', $stringValue, $options['min']));
			}
		} else {
			if (intval($stringValue) < intval($options['min'])) {
				throw new MinValueException(
					sprintf('The value "%s" is less than minimum value "%d".', $stringValue, $options['min']));
			}
		}
		return true;
	}

}
