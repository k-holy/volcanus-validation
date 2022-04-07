<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\PhoneException;

/**
 * PhoneChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class PhoneChecker extends AbstractChecker
{

    public static $forVector = false;

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['locale'] = null; // ロケール
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が郵便番号として妥当か検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     */
    public function check($value, array $options = []): bool
    {
        $options = Util::mergeOptions($this->options, $options);

        $locale = $options['locale'];
        $stringValue = (string)$value;
        switch ($locale) {
            case 'jp':
                if (!preg_match('/\A(0[0-9]+)-([0-9]+)-([0-9]+)\z/', $stringValue)) {
                    throw new PhoneException(
                        'The value is not of the Japanese phone format.');
                }
                break;
            // 国際電話表記
            default:
                if (!preg_match('/\A(\+)*([0-9][0-9\-\040]+[0-9])\z/', $stringValue)) {
                    throw new PhoneException(
                        'The value is not of the phone format.');
                }
                break;
        }
        return true;
    }

}
