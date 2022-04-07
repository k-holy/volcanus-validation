<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\AcceptCharactersException;

/**
 * AcceptCharactersChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class AcceptCharactersChecker extends AbstractChecker
{

    public static $forVector = false;

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['acceptCharacters'] = null; // 許容する文字列
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が指定した文字だけで構成されているか検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     */
    public function check($value, array $options = []): bool
    {
        $options = Util::mergeOptions($this->options, $options);
        $acceptCharacters = $options['acceptCharacters'];
        if (!isset($acceptCharacters)) {
            throw new \InvalidArgumentException(
                'The parameter "acceptCharacters" is not specified.');
        }
        if (strspn($value, $acceptCharacters) !== strlen($value)) {
            throw new AcceptCharactersException(
                'The value contains characters other than the specified character.');
        }
        return true;
    }

}
