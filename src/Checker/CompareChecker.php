<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\CompareException;

/**
 * CompareChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class CompareChecker extends AbstractChecker
{

    public static $forVector = false;

    const IS_EQUAL_TO = 'eq';
    const IS_NOT_EQUAL = 'ne';
    const IS_GREATER_THAN = 'gt';
    const IS_LESS_THAN = 'lt';
    const IS_GREATER_THAN_OR_EQUAL_TO = 'ge';
    const IS_LESS_THAN_OR_EQUAL_TO = 'le';

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['operator'] = self::IS_EQUAL_TO; // 比較演算子
        $this->options['compareTo'] = null; // 比較対象値
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 2つの値の比較結果を検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     */
    public function check($value, array $options = []): bool
    {
        $options = Util::mergeOptions($this->options, $options);

        $operator = $options['operator'];
        $compareTo = $options['compareTo'];

        if (!isset($operator)) {
            throw new \InvalidArgumentException(
                'The parameter "operator" is not specified.');
        }

        if (!isset($compareTo)) {
            throw new \InvalidArgumentException(
                'The parameter "compareTo" is not specified.');
        }

        switch ($operator) {
            case 'eq':
            case self::IS_EQUAL_TO:
                if (false === ($value == $compareTo)) {
                    throw new CompareException(
                        'The value {1} is not equal to value {2}.',
                        CompareException::INVALID_EQUAL_TO);
                }
                break;
            case 'ne':
            case self::IS_NOT_EQUAL:
                if (false === ($value != $compareTo)) {
                    throw new CompareException(
                        'The value {1} is equal to value {2}.',
                        CompareException::INVALID_NOT_EQUAL);
                }
                break;
            case 'gt':
            case self::IS_GREATER_THAN:
                if (false === ($value > $compareTo)) {
                    throw new CompareException(
                        'The value {1} is not greater than value {2}.',
                        CompareException::INVALID_GREATER_THAN);
                }
                break;
            case 'lt':
            case self::IS_LESS_THAN:
                if (false === ($value < $compareTo)) {
                    throw new CompareException(
                        'The value {1} is not less than value {2}.',
                        CompareException::INVALID_LESS_THAN);
                }
                break;
            case 'ge':
            case self::IS_GREATER_THAN_OR_EQUAL_TO:
                if (false === ($value >= $compareTo)) {
                    throw new CompareException(
                        'The value {1} is not greater than value {2} or not equal to value {2}.',
                        CompareException::INVALID_GREATER_THAN_OR_EQUAL_TO);
                }
                break;
            case 'le':
            case self::IS_LESS_THAN_OR_EQUAL_TO:
                if (false === ($value <= $compareTo)) {
                    throw new CompareException(
                        'The value {1} is not less than value 2 or not equal to value {2}.',
                        CompareException::INVALID_LESS_THAN_OR_EQUAL_TO);
                }
                break;
            default:
                throw new \InvalidArgumentException(
                    sprintf('The parameter "%s" is unsupported operator.', $operator));
        }
        return true;
    }

}
