<?php
/**
 * Volcanus libraries for PHP 8.3~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Exception\CheckerException;

/**
 * CompareException
 *
 * @author k.holy74@gmail.com
 */
class CompareException extends AbstractCheckerException
{
    const int INVALID_EQUAL_TO = 1;
    const int INVALID_NOT_EQUAL = 2;
    const int INVALID_GREATER_THAN = 3;
    const int INVALID_LESS_THAN = 4;
    const int INVALID_GREATER_THAN_OR_EQUAL_TO = 5;
    const int INVALID_LESS_THAN_OR_EQUAL_TO = 6;
}
