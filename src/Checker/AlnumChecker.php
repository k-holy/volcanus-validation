<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\AlnumException;

/**
 * AlnumChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class AlnumChecker extends AbstractChecker
{

    public static $forVector = false;

    public function __construct(array $options = [])
    {
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が英字と10進数の数字だけで構成されているか検証します。
     *
     * @param  mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param  array $options 検証オプション
     * @return boolean 検証結果
     */
    public function check($value, array $options = [])
    {
        $stringValue = (string)$value;
        if (!ctype_alnum($stringValue)) {
            throw new AlnumException(
                'The value contains characters other than the alphabet or numeric.');
        }
        return true;
    }

}
