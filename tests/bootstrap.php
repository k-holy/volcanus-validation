<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
error_reporting(E_ALL | E_STRICT | E_DEPRECATED);

$loader = include realpath(__DIR__ . '/../vendor/autoload.php');
$loader->add('Volcanus\Validation\Test', __DIR__);
