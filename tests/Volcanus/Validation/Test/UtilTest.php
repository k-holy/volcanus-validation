<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Test;

use PHPUnit\Framework\TestCase;
use Volcanus\Validation\Util;

/**
 * UtilTest
 *
 * @author k.holy74@gmail.com
 */
class UtilTest extends TestCase
{

    private function getArray(): array
    {
        $arr = [];
        $arr[0] = 1;
        $arr[1] = [];
        $arr[1][0] = 2;
        $arr[1][1] = 3;
        $arr[1][2] = [];
        $arr[1][2][0] = 4;
        $arr[1][2][1] = 5;
        $arr[1][2][2] = [];
        $arr[1][2][2][0] = 6;
        $arr[1][2][2][1] = 7;
        $arr[1][2][2][2] = [];
        $arr[1][2][2][2][0] = 8;
        $arr[1][2][2][2][1] = 9;
        $arr[2] = 10;
        $arr[3] = [];
        $arr[3][0] = 11;
        $arr[3][1] = [];
        $arr[3][1][0] = 12;
        $arr[3][1][1] = [];
        $arr[3][1][1][0] = 13;
        return $arr;
    }

    public function testRecursiveCheckIsOK()
    {
        $options = [];
        $values = $this->getArray();
        $this->assertTrue(Util::recursiveCheck(function ($value) {
            return (is_int($value));
        }, $values, $options));
    }

    public function testRecursiveCheckIsNg()
    {
        $options = [];
        $values = $this->getArray();
        $this->assertFalse(Util::recursiveCheck(function ($value) {
            return ($value > 10);
        }, $values, $options));
    }

    public function testMap()
    {
        $values = $this->getArray();
        $values = Util::map(function ($val) {
            return $val * 2;
        }, $values);
        $this->assertEquals(2, $values[0]);
        $this->assertEquals(4, $values[1][0]);
        $this->assertEquals(6, $values[1][1]);
        $this->assertEquals(8, $values[1][2][0]);
        $this->assertEquals(10, $values[1][2][1]);
        $this->assertEquals(12, $values[1][2][2][0]);
        $this->assertEquals(14, $values[1][2][2][1]);
        $this->assertEquals(16, $values[1][2][2][2][0]);
        $this->assertEquals(18, $values[1][2][2][2][1]);
        $this->assertEquals(20, $values[2]);
        $this->assertEquals(22, $values[3][0]);
        $this->assertEquals(24, $values[3][1][0]);
        $this->assertEquals(26, $values[3][1][1][0]);
    }


}
