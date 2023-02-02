<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\GraphException;

/**
 * GraphChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class GraphChecker extends AbstractChecker
{

    public static bool $forVector = false;

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が表示可能な文字だけで構成されているか検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     */
    public function check(mixed $value, array $options = []): bool
    {
        $stringValue = (string)$value;
        if (!ctype_graph($stringValue)) {
            throw new GraphException(
                'The value contains characters that cannot be displayed.');
        }
        return true;
    }

}
