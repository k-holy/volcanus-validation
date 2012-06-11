<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
spl_autoload_register(function($className) {
	$namespace = 'Volcanus\Validation';
	if (0 === strpos(ltrim($className, '/'), $namespace)) {
		$path = realpath(__DIR__ . '/..') . substr(
			str_replace('\\', '/', $className),
			strlen($namespace)
		).'.php';
		if (file_exists($path)) {
			return include $path;
		}
	}
	return false;
}, true, true);
