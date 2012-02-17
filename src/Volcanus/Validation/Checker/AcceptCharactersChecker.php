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
use Volcanus\Validation\Exception\InvalidArgumentException;
use Volcanus\Validation\Exception\CheckerException\AcceptCharactersException;

/**
 * AcceptCharactersChecker
 *
 * @author     k.holy74@gmail.com
 */
class AcceptCharactersChecker extends AbstractChecker
{

	public function __construct(array $options = array())
	{
		$this->options['acceptCharacters'] = null; // 許容する文字列
		$this->options = Util::mergeOptions($this->options, $options);
	}

	/**
	 * 値が指定した文字だけで構成されているか検証します。
	 *
	 * @param  mixed   検証値 (文字列または__toStringメソッド実装オブジェクト)
	 * @param  array   検証オプション
	 * @return boolean 検証結果
	 */
	public function check($value, array $options = array())
	{
		$options = Util::mergeOptions($this->options, $options);
		$acceptCharacters = $options['acceptCharacters'];
		if (!isset($acceptCharacters)) {
			throw new InvalidArgumentException(
				'The parameter "acceptCharacters" is not specified.');
		}
		if (strspn($value, $acceptCharacters) !== strlen($value)) {
			throw new AcceptCharactersException(
				'The value contains characters other than the specified character.');
		}
		return true;
	}

}
