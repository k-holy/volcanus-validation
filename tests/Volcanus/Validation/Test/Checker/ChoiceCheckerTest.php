<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\ChoiceChecker;
use Volcanus\Validation\Exception\CheckerException\ChoiceException;

/**
 * ChoiceCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class ChoiceCheckerTest extends TestCase
{

    /** @var  ChoiceChecker */
    protected ChoiceChecker $checker;

    public function setUp(): void
    {
        $this->checker = new ChoiceChecker();
    }

    public function testCheckIsOk()
    {
        $this->assertTrue($this->checker->check('1', ['choices' => ['1', '2', '3']]));
        $this->assertTrue($this->checker->check('2', ['choices' => ['1', '2', '3']]));
        $this->assertTrue($this->checker->check('3', ['choices' => ['1', '2', '3']]));
        $this->assertTrue($this->checker->check('1', ['choices' => '1,2,3']));
        $this->assertTrue($this->checker->check('2', ['choices' => '1,2,3']));
        $this->assertTrue($this->checker->check('3', ['choices' => '1,2,3']));
    }

    public function testRaiseCheckerExceptionWhenCheckIsNgByInvalidChoice()
    {
        $this->expectException(ChoiceException::class);
        $this->checker->check('4', ['choices' => ['1', '2', '3']]);
    }

    public function testRaiseInvalidArgumentExceptionWhenInvalidChoicesParameterIsSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check('1', ['choices' => 'Foo']);
    }

    public function testInvokeMethod()
    {
        $this->expectException(ChoiceException::class);
        $checker = $this->checker;
        $checker->setOption('choices', ['1', '2', '3']);
        $checker('4');
    }

}
