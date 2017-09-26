<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */

namespace Volcanus\Validation\Exception\CheckerException;

/**
 * DatetimeException
 *
 * @package    Volcanus\Validation
 * @author     k.holy74@gmail.com
 */
class DatetimeException extends AbstractCheckerException
{
    const INVALID_FORMAT = 1;
    const DATE_OUT_OF_RANGE = 2;
    const HOURS_OUT_OF_RANGE = 3;
    const MINUTES_OUT_OF_RANGE = 4;
    const SECONDS_OUT_OF_RANGE = 5;
}
