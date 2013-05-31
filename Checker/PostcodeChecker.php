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
use Volcanus\Validation\Exception\CheckerException\PostcodeException;

/**
 * PostcodeChecker
 *
 * @author     k.holy74@gmail.com
 */
class PostcodeChecker extends AbstractChecker
{

	public function __construct(array $options = array())
	{
		$this->options['locale'] = null; // ロケール
		$this->options['acceptArray'] = true;
		$this->options = Util::mergeOptions($this->options, $options);
	}

	/**
	 * 値が郵便番号として妥当か検証します。
	 *
	 * @param  mixed   検証値 (文字列または__toStringメソッド実装オブジェクト)
	 * @param  array   検証オプション
	 * @return boolean 検証結果
	 */
	public function check($value, array $options = array())
	{
		$options = Util::mergeOptions($this->options, $options);

		$locale = $options['locale'];
		$stringValue = (string)$value;
		switch ($locale) {
		case 'jp':
		default:
			if (!preg_match('/\A([0-9]{3})\-*([0-9]{4})\z/', $stringValue, $matches)) {
				throw new PostcodeException(
					'The value is not of the Japanese postcode format.');
			}
			break;
		}
		return true;
	}

}
