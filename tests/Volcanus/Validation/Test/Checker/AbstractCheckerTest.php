<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\AbstractChecker;

/**
 * AbstractCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class AbstractCheckerTest extends TestCase
{

    public function testGetInstance()
    {
        $this->assertInstanceOf('\Volcanus\Validation\Checker\AbstractChecker',
            NumericChecker::getInstance());
    }

    public function testCheck()
    {
        $checker = new NumericChecker(['min' => 1, 'max' => 10]);
        $this->assertTrue($checker->check(1));
        $this->assertTrue($checker->check(10));
        $this->assertFalse($checker->check('a'));
        $this->assertFalse($checker->check(0));
        $this->assertFalse($checker->check(11));
    }

    public function testInvoke()
    {
        $checker = new NumericChecker(['min' => 1, 'max' => 10]);
        $this->assertTrue($checker(1));
        $this->assertTrue($checker(10));
        $this->assertFalse($checker('a'));
        $this->assertFalse($checker(0));
        $this->assertFalse($checker(11));
        $this->assertTrue($checker(11, ['max' => 11]));
    }

    public function testGuard()
    {
        $checker = new NumericChecker(['min' => 1, 'max' => 10]);
        $this->assertFalse($checker->guard(null));
        $this->assertFalse($checker->guard(''));
        $this->assertFalse($checker->guard([]));
        $this->assertFalse($checker->guard(new \ArrayIterator([])));
        $this->assertTrue($checker->guard(1));
        $this->assertTrue($checker->guard(' '));
        $this->assertTrue($checker->guard([null]));
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

    public function testRaiseInvalidArgumentExceptionWhenUndefinedOptionIsSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
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
class NumericChecker extends AbstractChecker
{

    public function __construct(array $options = [])
    {
        $this->options['min'] = null;
        $this->options['max'] = null;
        $this->options = array_replace_recursive($this->options, $options);
    }

    public function check(mixed $value, array $options = []): bool
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
