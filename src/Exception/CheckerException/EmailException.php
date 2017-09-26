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
 * EmailException
 *
 * @package    Volcanus\Validation
 * @author     k.holy74@gmail.com
 */
class EmailException extends AbstractCheckerException
{
    const INVALID_FORMAT = 1;
    const INVALID_ADDR_SPEC = 2;
    const INVALID_LOCAL_PART = 3;
    const INVALID_DOMAIN = 4;
}
