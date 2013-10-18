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
use Volcanus\Validation\Exception\CheckerException\MinValueException;
use Volcanus\Validation\Exception\CheckerException\MaxValueException;

/**
 * FloatChecker
 *
 * @author     k.holy74@gmail.com
 */
class FloatChecker extends AbstractChecker
{

	public static $forVector = false;

	public function __construct(array $options = array())
	{
		$this->options['min'] = null; // 最小値
		$this->options['max'] = null; // 最大値
		$this->options['unsigned'] = false; // +-を有効とするかどうか
		$this->options['acceptArray'] = true;
		$this->options = Util::mergeOptions($this->options, $options);
	}

	/**
	 * 値が+-符号および10進数の数字だけで構成されているか検証します。
	 *
	 * @param  mixed   検証値 (文字列または__toStringメソッド実装オブジェクト)
	 * @param  array   検証オプション
	 * @return boolean 検証結果
	 */
	public function check($value, array $options = array())
	{
		$options = Util::mergeOptions($this->options, $options);

		$min = $options['min'];
		$max = $options['max'];

		try {
			Util::checkFloat($value, $options['unsigned']);
		} catch (\Exception $e) {
			throw $e;
		}

		if (isset($min)) {
			if (!is_int($min) && !is_float($min)) {
				throw new \InvalidArgumentException(
					'The min parameter is not integer and not float.');
			}
			if ($value < $min) {
				throw new MinValueException(
					'The value is less than min parameter.');
			}
		}

		if (isset($max)) {
			if (!is_int($max) && !is_float($max)) {
				throw new \InvalidArgumentException(
					'The max parameter is not integer and not float.');
			}
			if ($value > $max) {
				throw new MaxValueException(
					'The value is greater than max parameter.');
			}
		}
		return true;
	}

}
