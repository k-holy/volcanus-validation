<?php
/**
 * Volcanus libraries for PHP 8.1~
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
    const INVALID_NULL = 1;
    const EMPTY_STRING = 2;
    const EMPTY_ARRAY = 3;
}
