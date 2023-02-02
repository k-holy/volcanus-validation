<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Exception\CheckerException;

/**
 * DatetimeException
 *
 * @author k.holy74@gmail.com
 */
class DatetimeException extends AbstractCheckerException
{
    const INVALID_FORMAT = 1;
    const DATE_OUT_OF_RANGE = 2;
    const HOURS_OUT_OF_RANGE = 3;
    const MINUTES_OUT_OF_RANGE = 4;
    const SECONDS_OUT_OF_RANGE = 5;
}
