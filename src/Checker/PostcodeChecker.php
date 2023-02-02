<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\PostcodeException;

/**
 * PostcodeChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class PostcodeChecker extends AbstractChecker
{

    public static bool $forVector = false;

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
    public function check(mixed $value, array $options = []): bool
    {
        $options = Util::mergeOptions($this->options, $options);

        $locale = $options['locale'];
        $stringValue = (string)$value;
        switch ($locale) {
            case 'jp':
            default:
                if (!preg_match('/\A([0-9]{3})-*([0-9]{4})\z/', $stringValue)) {
                    throw new PostcodeException(
                        'The value is not of the Japanese postcode format.');
                }
                break;
        }
        return true;
    }

}
