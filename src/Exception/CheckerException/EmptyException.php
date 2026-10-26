<?php
/**
 * Volcanus libraries for PHP 8.3~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Exception\CheckerException;

/**
 * EmptyException
 *
 * @author k.holy74@gmail.com
 */
class EmptyException extends AbstractCheckerException
{
    const int INVALID_NULL = 1;
    const int EMPTY_STRING = 2;
    const int EMPTY_ARRAY = 3;
}
