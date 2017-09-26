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
use Volcanus\Validation\Exception\CheckerException\AlphaException;

/**
 * AlphaChecker
 *
 * @author     k.holy74@gmail.com
 */
class AlphaChecker extends AbstractChecker
{

    public static $forVector = false;

    public function __construct(array $options = array())
    {
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が英字だけで構成されているか検証します。
     *
     * @param  mixed   検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param  array   検証オプション
     * @return boolean 検証結果
     */
    public function check($value, array $options = array())
    {
        $stringValue = (string)$value;
        if (!ctype_alpha($stringValue)) {
            throw new AlphaException(
                'The value contains characters other than the alphabet.');
        }
        return true;
    }

}
