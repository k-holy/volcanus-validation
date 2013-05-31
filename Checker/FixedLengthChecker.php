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

/**
 * FixedLengthChecker
 *
 * @author     k.holy74@gmail.com
 */
class FixedLengthChecker extends AbstractChecker
{

	public function __construct(array $options = array())
	{
		$this->options['length'  ] = null; // 文字長
		$this->options['encoding'] = null; // 文字エンコーディング
		$this->options['mbLength'] = self::LENGTH_CHARS; // 文字長測定モード 'B':バイト長 'C':文字長 'W':文字幅
		$this->options['acceptArray'] = true;
		$this->options = Util::mergeOptions($this->options, $options);
	}

	/**
	 * 値の文字長が指定値であるか検証します。
	 *
	 * @param  mixed   検証値 (文字列または__toStringメソッド実装オブジェクト)
	 * @param  array   検証オプション
	 * @return boolean 検証結果
	 */
	public function check($value, array $options = array())
	{
		$options = Util::mergeOptions($this->options, $options);

		if (!isset($options['length'])) {
			throw new \InvalidArgumentException(
				'The parameter "length" is not specified.');
		}

		if (!is_int($options['length'])) {
			throw new \InvalidArgumentException(
				'The parameter "length" is not integer.');
		}

		try {

			if (isset($options['encoding'])) {
				Util::checkEncoding($value, $options['encoding']);
			}

			Util::checkMinLength($value,
				$options['length'],
				(isset($options['mbLength'])) ? $options['mbLength'] : null,
				(isset($options['encoding'])) ? $options['encoding'] : null
			);

			Util::checkMaxLength($value,
				$options['length'],
				(isset($options['mbLength'])) ? $options['mbLength'] : null,
				(isset($options['encoding'])) ? $options['encoding'] : null
			);

		} catch (\Exception $e) {
			throw $e;
		}
		return true;
	}

}
