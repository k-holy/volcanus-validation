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
use Volcanus\Validation\Exception\CheckerException\UriException;

/**
 * UriChecker
 *
 * @author     k.holy74@gmail.com
 */
class UriChecker extends AbstractChecker
{

	public static $uriPattern = '~\A(([a-z][a-z0-9+-.]+):)(//([^/?#]*))?([^?#]+)(\?([^#]*))?(#(.*))?\z~i'; // required scheme,path

	public function __construct(array $options = array())
	{
		$this->options['acceptScheme'] = 'http,https'; // 受け入れるスキーム
		$this->options = Util::mergeOptions($this->options, $options);
	}

	/**
	 * 値がURIとして妥当か検証します。
	 *
	 * URI = scheme ":" "//" [ userinfo "@" ] reg-name [ ":" port ] *( "/" segment ) [ "?" query ] [ "#" fragment ]
	 *
	 * scheme      = ALPHA *( ALPHA / DIGIT / "+" / "-" / "." )
	 * userinfo    = *( unreserved / pct-encoded / sub-delims / ":" )
	 * reg-name    = *( unreserved / pct-encoded / sub-delims )
	 * port        = *DIGIT
	 * segment     = *( unreserved / pct-encoded / sub-delims / ":" / "@" )
	 * query       = *( unreserved / pct-encoded / sub-delims / ":" / "@" / "/" / "?" )
	 * fragment    = *( unreserved / pct-encoded / sub-delims / ":" / "@" / "/" / "?" )
	 *
	 * pct-encoded    = "%" HEXDIG HEXDIG
	 * unreserved     = ALPHA / DIGIT / "-" / "." / "_" / "~"
	 * gen-delims     = ":" / "/" / "?" / "#" / "[" / "]" / "@"
	 * sub-delims     = "!" / "$" / "&" / "'" / "(" / ")" / "*" / "+" / "," / ";" / "="
	 *
	 * Uniform Resource Identifier (URI): Generic Syntax
	 * http://www.ietf.org/rfc/rfc3986.txt
	 *
	 * @param  mixed   検証値 (文字列または__toStringメソッド実装オブジェクト)
	 * @param  array   検証オプション
	 * @return boolean 検証結果
	 */
	public function check($value, array $options = array())
	{
		$options = Util::mergeOptions($this->options, $options);

		$acceptSchemes = $options['acceptScheme'];
		$stringValue = (string)$value;

		if (!preg_match(self::$uriPattern, $stringValue, $matches)) {
			throw new UriException(
				'The value is not valid Uri.');
		}

		$scheme = $matches[2];
		if (isset($acceptSchemes) && isset($scheme)) {
			$found = false;
			foreach (explode(',', $acceptSchemes) as $acceptScheme) {
				if (strcasecmp($scheme, $acceptScheme) == 0) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				throw new UriException(
					sprintf('The scheme "%s" is not accepted.', $scheme));
			}
		}
		return true;
	}

}
