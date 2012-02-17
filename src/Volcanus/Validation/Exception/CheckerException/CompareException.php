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
 * CompareException
 *
 * @package    Volcanus\Validation
 * @author     k.holy74@gmail.com
 */
class CompareException extends AbstractCheckerException
{
	const INVALID_EQUAL_TO                 = 1;
	const INVALID_NOT_EQUAL                = 2;
	const INVALID_GREATER_THAN             = 3;
	const INVALID_LESS_THAN                = 4;
	const INVALID_GREATER_THAN_OR_EQUAL_TO = 5;
	const INVALID_LESS_THAN_OR_EQUAL_TO    = 6;
}
