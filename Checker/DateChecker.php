<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\DateException;

/**
 * DateChecker
 *
 * @author     k.holy74@gmail.com
 */
class DateChecker extends AbstractChecker
{

	/* 日付解析用パターン */
	const PATTERN_YMD    = 'ymd';
	const PATTERN_YMDH   = 'ymdh';
	const PATTERN_YMDHI  = 'ymdhi';
	const PATTERN_YMDHIS = 'ymdhis';

	/* 日付解析用順序 */
	const ORDER_YMD    = 'ymd';
	const ORDER_YMDH   = 'ymdh';
	const ORDER_YMDHI  = 'ymdhi';
	const ORDER_YMDHIS = 'ymdhis';

	/* 日付解析用正規表現 */
	const PREG_PATTERN_YMD    = '#\A(\d+)[-/](\d{1,2})[-/](\d{1,2})\z#';
	const PREG_PATTERN_YMDH   = '#\A(\d+)[-/](\d{1,2})[-/](\d{1,2}) *(\d{1,2})\z#';
	const PREG_PATTERN_YMDHI  = '#\A(\d+)[-/](\d{1,2})[-/](\d{1,2}) *(\d{1,2}):(\d{1,2})\z#';
	const PREG_PATTERN_YMDHIS = '#\A(\d+)[-/](\d{1,2})[-/](\d{1,2}) *(\d{1,2}):(\d{1,2}):(\d{1,2})\z#';

	public function __construct(array $options = array())
	{
		$this->options['pattern'] = self::PREG_PATTERN_YMD; // 日付解析用パターン
		$this->options['order'  ] = self::ORDER_YMD; // 日付解析用順序
		$this->options = Util::mergeOptions($this->options, $options);
	}

	/**
	 * 値が日付を表す文字列であるか検証します。
	 * 年の範囲: 1-32767 (checkdate関数の制限による)
	 * 書式    : 年月日(必須) YYYY-MM-DD または Y-M-D
	 *           時分秒(任意) HH:II:SS   または H:I:S
	 *
	 * @param  mixed   検証値 (文字列または__toStringメソッド実装オブジェクト)
	 * @param  array   検証オプション
	 * @return boolean 検証結果
	 */
	public function check($value, array $options = array())
	{
		$options = Util::mergeOptions($this->options, $options);

		$pattern = $options['pattern'];
		$order   = $options['order'];

		$stringValue = (string)$value;
		$parsed = $this->parseDate($stringValue, $pattern, $order);
		if (false === $parsed) {
			throw new DateException(
				'Invalid date format.',
				DateException::INVALID_FORMAT);
		}

		if (isset($parsed['y']) && isset($parsed['m']) && isset($parsed['d'])) {
			if (!checkdate($parsed['m'], $parsed['d'], $parsed['y'])) {
				throw new DateException(
					sprintf('The date "%d-%d-%d" is out of range.', $parsed['y'], $parsed['m'], $parsed['d']),
					DateException::DATE_OUT_OF_RANGE);
			}
		}

		if (isset($parsed['h'])) {
			if ($parsed['h'] < 0 || $parsed['h'] > 23) {
				throw new DateException(
					sprintf('The hours part "%d" is out of range.', $parsed['h']),
					DateException::HOURS_OUT_OF_RANGE);
			}
		}

		if (isset($parsed['i'])) {
			if ($parsed['i'] < 0 || $parsed['i'] > 59) {
				throw new DateException(
					sprintf('The minutes part "%d" is out of range.', $parsed['i']),
					DateException::MINUTES_OUT_OF_RANGE);
			}
		}

		if (isset($parsed['s'])) {
			if ($parsed['s'] < 0 || $parsed['s'] > 59) {
				throw new DateException(
					sprintf('The seconds part "%d" is out of range.', $parsed['s']),
					DateException::SECONDS_OUT_OF_RANGE);
			}
		}
		return true;
	}

	/**
	 * 日付文字列を解析し、日付部分ごとに値を数値化した配列を返します。
	 *
	 * @param  string 日付文字列
	 * @return mixed  日付部分の配列または FALSE
	 */
	private function parseDate($value, $pattern, $order)
	{

		$parsed = array();
		switch ($pattern) {
		case self::PATTERN_YMDHIS:
			$pattern = self::PREG_PATTERN_YMDHIS;
			$order = self::ORDER_YMDHIS;
			break;
		case self::PATTERN_YMDHI:
			$pattern = self::PREG_PATTERN_YMDHI;
			$order = self::ORDER_YMDHI;
			break;
		case self::PATTERN_YMDH:
			$pattern = self::PREG_PATTERN_YMDH;
			$order = self::ORDER_YMDH;
			break;
		case self::PATTERN_YMD:
			$pattern = self::PREG_PATTERN_YMD;
			$order = self::ORDER_YMD;
			break;
		}
		if (!preg_match($pattern, $value, $matches)) {
			return false;
		}
		$parts = array('y', 'm', 'd', 'h', 'i', 's');
		$partsCount = 0;
		foreach ($parts as $part) {
			$pos = strpos($order, $part);
			if ($pos !== false) {
				$partsCount++;
				$index = $pos + 1;
				if (isset($matches[$index])) {
					$parsed[$part] = (int)$matches[$index];
				}
			}
		}
		if (count($parsed) !== $partsCount) {
			return false;
		}
		return $parsed;
	}

}
