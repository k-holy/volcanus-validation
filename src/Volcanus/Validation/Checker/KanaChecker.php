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
use Volcanus\Validation\Exception\CheckerException\KanaException;

/**
 * KanaChecker
 *
 * @author     k.holy74@gmail.com
 */
class KanaChecker extends AbstractChecker
{

	public function __construct(array $options = array())
	{
		$this->options['acceptFlag' ] = 'HKk'; // // 許容する文字列
		$this->options['encoding'   ] = null; // 文字エンコーディング
		$this->options['acceptSpace'] = false; // 空白を許可する
		$this->options = Util::mergeOptions($this->options, $options);
	}

	/**
	 * 値が表示可能な文字だけで構成されているか検証します。
	 *
	 * @param  mixed   検証値 (文字列または__toStringメソッド実装オブジェクト)
	 * @param  array   検証オプション
	 * @return boolean 検証結果
	 */
	public function check($value, array $options = array())
	{
		$options = Util::mergeOptions($this->options, $options);

		$acceptFlag  = $options['acceptFlag'];
		$encoding    = $options['encoding'];
		$acceptSpace = $options['acceptSpace'];
		$stringValue = (string)$value;

		$validationEncoding = 'UTF-8';
		if (!isset($encoding)) {
			$encoding = mb_internal_encoding();
		}
		if (strcmp($validationEncoding, $encoding) != 0) {
			$stringValue = mb_convert_encoding($stringValue, $validationEncoding, $encoding);
		}

		$patterns = array();
		// 全角かな
		if (false !== strpos($acceptFlag, 'H')) {
 			$patterns[] = '(\xe3(\x81[\x81-\xbf]|\x82[\x80-\x93]))';
		}
		// 全角カナ
		if (false !== strpos($acceptFlag, 'K')) {
 			$patterns[] = '(\xe3(\x82[\xa1-\xbf]|\x83[\x80-\xb6]))';
		}
		// 半角ｶﾅ
		if (false !== strpos($acceptFlag, 'k')) {
 			$patterns[] = '(\xef(\xbd[\xa1-\xbf]|\xbe[\x80-\x9f]))';
		}
		// 全角かな＋全角カナ記号（・ーヽヾ）
		if (false !== strpos($acceptFlag, 'H') || false !== strpos($acceptFlag, 'K')) {
 			$patterns[] = '(\xe3\x83[\xbb-\xbe])';
		}
		// 空白を許可
		if ($acceptSpace) {
			$patterns[] = '\040';
		}
		if (!preg_match(sprintf('/\A(%s)+\z/', join('|', $patterns)), $stringValue)) {
			throw new KanaException(
				'The value contains characters that cannot be displayed.');
		}
		return true;
	}

}
