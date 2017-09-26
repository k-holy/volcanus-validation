<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

/**
 * AbstractCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class AbstractCheckerTest extends \PHPUnit_Framework_TestCase
{

    public function testGetInstance()
    {
        $this->assertInstanceOf('\Volcanus\Validation\Checker\AbstractChecker',
            NumericChecker::getInstance());
    }

    public function testCheck()
    {
        $checker = new NumericChecker(array('min' => 1, 'max' => 10));
        $this->assertTrue($checker->check(1));
        $this->assertTrue($checker->check(10));
        $this->assertFalse($checker->check('a'));
        $this->assertFalse($checker->check(0));
        $this->assertFalse($checker->check(11));
    }

    public function testInvoke()
    {
        $checker = new NumericChecker(array('min' => 1, 'max' => 10));
        $this->assertTrue($checker(1));
        $this->assertTrue($checker(10));
        $this->assertFalse($checker('a'));
        $this->assertFalse($checker(0));
        $this->assertFalse($checker(11));
        $this->assertTrue($checker(11, array('max' => 11)));
    }

    public function testGuard()
    {
        $checker = new NumericChecker(array('min' => 1, 'max' => 10));
        $this->assertFalse($checker->guard(null));
        $this->assertFalse($checker->guard(''));
        $this->assertFalse($checker->guard(array()));
        $this->assertFalse($checker->guard(new \ArrayIterator(array())));
        $this->assertTrue($checker->guard(1));
        $this->assertTrue($checker->guard(' '));
        $this->assertTrue($checker->guard(array(null)));
    }

    public function testSetOption()
    {
        $checker = new NumericChecker();
        $this->assertTrue($checker(1));
        $this->assertTrue($checker(10));

        $checker->setOption('min', 2);
        $this->assertFalse($checker->check(1));

        $checker->setOption('max', 9);
        $this->assertFalse($checker->check(10));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseInvalidArgumentExceptionWhenUndefinedOptionIsSpecified()
    {
        $checker = new NumericChecker();
        $checker->setOption('foo', true);
    }

    public function testIsEnableOption()
    {
        $checker = new NumericChecker();
        $this->assertTrue($checker->isEnableOption('min'));
        $this->assertTrue($checker->isEnableOption('max'));
        $this->assertFalse($checker->isEnableOption('foo'));
    }

}

/**
 * @property array $options
 */
class NumericChecker extends \Volcanus\Validation\Checker\AbstractChecker
{

    public function __construct(array $options = array())
    {
        $this->options['min'] = null;
        $this->options['max'] = null;
        $this->options = array_replace_recursive($this->options, $options);
    }

    public function check($value, array $options = array())
    {
        $options = array_replace_recursive($this->options, $options);
        if (!preg_match('/\A\d+\z/', $value)) {
            return false;
        }
        if (isset($options['min']) && $options['min'] > $value) {
            return false;
        }
        if (isset($options['max']) && $options['max'] < $value) {
            return false;
        }
        return true;
    }
}
