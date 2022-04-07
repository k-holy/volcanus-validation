<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Exception\CheckerException\DateException;
use Volcanus\Validation\Exception\CheckerException\DatetimeException;
use Volcanus\Validation\Util;

/**
 * DatetimeChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class DatetimeChecker extends AbstractChecker
{

    public static $forVector = false;

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値が日時を表す文字列であるか検証します。
     * 年の範囲: 1-32767 (checkdate関数の制限による)
     * 書式    : 年月日(必須) YYYY-MM-DD または Y-M-D
     *           時分秒(必須) HH:II:SS   または H:I:S
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     */
    public function check($value, array $options = []): bool
    {
        try {
            DateChecker::getInstance([
                'pattern' => DateChecker::PATTERN_YMDHIS,
                'order' => 'ymdhis',
            ])->check($value, $options);
        } catch (\Exception $e) {
            if ($e instanceof DateException) {
                switch ($e->getCode()) {
                    case DateException::INVALID_FORMAT:
                        throw new DatetimeException($e->getMessage(), DatetimeException::INVALID_FORMAT);
                    case DateException::DATE_OUT_OF_RANGE:
                        throw new DatetimeException($e->getMessage(), DatetimeException::DATE_OUT_OF_RANGE);
                    case DateException::HOURS_OUT_OF_RANGE:
                        throw new DatetimeException($e->getMessage(), DatetimeException::HOURS_OUT_OF_RANGE);
                    case DateException::MINUTES_OUT_OF_RANGE:
                        throw new DatetimeException($e->getMessage(), DatetimeException::MINUTES_OUT_OF_RANGE);
                    case DateException::SECONDS_OUT_OF_RANGE:
                        throw new DatetimeException($e->getMessage(), DatetimeException::SECONDS_OUT_OF_RANGE);
                }
            }
            throw $e;
        }
        return true;
    }


}
