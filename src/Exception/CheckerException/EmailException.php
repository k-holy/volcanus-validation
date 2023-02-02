<?php
/**
 * Volcanus libraries for PHP 8.1~
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
    const INVALID_FORMAT = 1;
    const INVALID_ADDR_SPEC = 2;
    const INVALID_LOCAL_PART = 3;
    const INVALID_DOMAIN = 4;
}
