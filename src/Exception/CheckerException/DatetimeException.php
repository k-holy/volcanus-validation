<?php
/**
 * Volcanus libraries for PHP 8.3~
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
    const int INVALID_FORMAT = 1;
    const int DATE_OUT_OF_RANGE = 2;
    const int HOURS_OUT_OF_RANGE = 3;
    const int MINUTES_OUT_OF_RANGE = 4;
    const int SECONDS_OUT_OF_RANGE = 5;
}
