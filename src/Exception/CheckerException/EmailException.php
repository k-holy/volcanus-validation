<?php
/**
 * Volcanus libraries for PHP 8.3~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Exception\CheckerException;

/**
 * EmailException
 *
 * @author k.holy74@gmail.com
 */
class EmailException extends AbstractCheckerException
{
    const int INVALID_FORMAT = 1;
    const int INVALID_ADDR_SPEC = 2;
    const int INVALID_LOCAL_PART = 3;
    const int INVALID_DOMAIN = 4;
}
