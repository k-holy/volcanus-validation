<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test\Checker;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Checker\AcceptCharactersChecker;
use Volcanus\Validation\Exception\CheckerException\AcceptCharactersException;

/**
 * AcceptCharactersCheckerTest
 *
 * @author k.holy74@gmail.com
 */
class AcceptCharactersCheckerTest extends TestCase
{

    /** @var  AcceptCharactersChecker */
    protected AcceptCharactersChecker $checker;

    public function setUp(): void
    {
        $this->checker = new AcceptCharactersChecker();
    }

    private function getAcceptCharacters(): string
    {
        return 'ABC123+-=@{}';
    }

    public function testCheckIsOk()
    {
        $options = [
            'acceptCharacters' => $this->getAcceptCharacters(),
        ];
        $this->assertTrue($this->checker->check('ABC', $options));
        $this->assertTrue($this->checker->check('123', $options));
        $this->assertTrue($this->checker->check('+-=@{}', $options));
        $this->assertTrue($this->checker->check('A1+=B2-@C3{}', $options));
    }

    public function testRaiseAcceptCharactersExceptionWhenCharacterOtherThanAcceptCharactersAreContained()
    {
        $this->expectException(AcceptCharactersException::class);
        $this->checker->check('A1+=B2-@C3{}%', [
            'acceptCharacters' => $this->getAcceptCharacters(),
        ]);
    }

    public function testInvokeMethod()
    {
        $this->expectException(AcceptCharactersException::class);
        $checker = $this->checker;
        $checker('A1+=B2-@C3{}%', [
            'acceptCharacters' => $this->getAcceptCharacters(),
        ]);
    }

    public function testRaiseInvalidArgumentExceptionWhenAcceptCharactersParameterIsNotSpecified()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->checker->check('A1+=B2-@C3{}');
    }

}
