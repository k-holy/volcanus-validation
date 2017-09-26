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
 * EmptyException
 *
 * @package    Volcanus\Validation
 * @author     k.holy74@gmail.com
 */
class EmptyException extends AbstractCheckerException
{
    const INVALID_NULL = 1;
    const EMPTY_STRING = 2;
    const EMPTY_ARRAY = 3;
}
