<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\AlphaException;

/**
 * AlphaChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class AlphaChecker extends AbstractChecker
{

    public static $forVector = false;

    /**
     * __construct
     *
     * @param  array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が英字だけで構成されているか検証します。
     *
     * @param  mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param  array $options 検証オプション
     * @return boolean 検証結果
     */
    public function check($value, array $options = [])
    {
        $stringValue = (string)$value;
        if (!ctype_alpha($stringValue)) {
            throw new AlphaException(
                'The value contains characters other than the alphabet.');
        }
        return true;
    }

}
