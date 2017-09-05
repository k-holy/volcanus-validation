<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\ChoiceException;

/**
 * ChoiceChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class ChoiceChecker extends AbstractChecker
{

    public static $forVector = false;

    /**
     * __construct
     *
     * @param  array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['choices'] = null; // 選択肢 (Array/Traversable または カンマ区切りの文字列)
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が指定された配列の要素に含まれる値と同一かどうかを検証します。
     *
     * @param  mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param  array $options 検証オプション
     * @return boolean 検証結果
     */
    public function check($value, array $options = [])
    {
        $options = Util::mergeOptions($this->options, $options);

        $choices = $options['choices'];

        if (!isset($choices)) {
            throw new \InvalidArgumentException(
                'The parameter "choices" is not specified.');
        }
        if (is_string($choices) && false !== strpos($choices, ',')) {
            $choices = explode(',', $choices);
        }
        if (!is_array($choices) && !($choices instanceof \Traversable)) {
            throw new \InvalidArgumentException(
                'The parameter "choices" is not valid Array/Traversable.');
        }

        $stringValue = (string)$value;

        $found = false;
        foreach ($choices as $choice) {
            if ($choice == $stringValue) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            throw new ChoiceException(
                'The value your choice is not valid.');
        }

        return true;
    }

}
