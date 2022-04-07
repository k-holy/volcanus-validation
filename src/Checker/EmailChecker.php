<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\Validation\Checker;

use Volcanus\Validation\Util;
use Volcanus\Validation\Exception\CheckerException\EmailException;
use Volcanus\Validation\Exception\CheckerException\MaxLengthException;

/**
 * EmailChecker
 *
 * @property array $options
 *
 * @author k.holy74@gmail.com
 */
class EmailChecker extends AbstractChecker
{

    public static $forVector = false;

    /**
     * __construct
     *
     * @param array $options 検証オプション
     */
    public function __construct(array $options = [])
    {
        $this->options['allowDotEndOfLocalPart'] = false; // ローカルパートの末尾に.を許可するかどうか
        $this->options['acceptArray'] = true;
        $this->options = Util::mergeOptions($this->options, $options);
    }

    /**
     * 値がメールアドレスとして妥当か検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param array $options 検証オプション
     * @return bool 検証結果
     * @throws \Exception
     */
    public function check($value, array $options = []): bool
    {
        $options = Util::mergeOptions($this->options, $options);

        $allowDotEndOfLocalPart = $options['allowDotEndOfLocalPart'];
        $stringValue = (string)$value;
        // name-addr 形式の場合は add-spec のみ切り出して検証する
        $valid = preg_match('/\A[^,<>]+<([^,<>]+)>\s*\z/', $stringValue, $matches)
            ? self::validateAddrSpec(trim($matches[1]), $allowDotEndOfLocalPart)
            : self::validateAddrSpec($value, $allowDotEndOfLocalPart);
        if (false === $valid) {
            throw new EmailException(
                'The value is invalid e-mail address.',
                EmailException::INVALID_FORMAT);
        }
        return true;
    }

    /**
     * メールアドレスを検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param bool $allowDotEndOfLocalPart ローカルパートの末尾に.を許可するかどうか
     * @return bool 検証結果
     * @throws \Exception
     */
    public static function validateAddrSpec($value, bool $allowDotEndOfLocalPart = false): bool
    {
        $stringValue = (string)$value;
        $separatorPosition = strrpos($stringValue, '@');
        $localPart = substr($stringValue, 0, $separatorPosition);
        $domain = substr($stringValue, $separatorPosition + 1);
        if (!self::validateLocalPart($localPart, $allowDotEndOfLocalPart)
            || !self::validateDomain($domain)) {
            throw new EmailException(
                'The value is invalid addr-spec.',
                EmailException::INVALID_ADDR_SPEC);
        }

        try {
            Util::checkMaxLength($value, 256, parent::LENGTH_BYTES);
        } catch (MaxLengthException $e) {
            throw new MaxLengthException(
                'A addr-spec of the e-mail address is limited within 256 bytes.');
        } catch (\Exception $e) {
            throw $e;
        }
        return true;
    }

    /**
     * メールアドレスのローカルパートを検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @param bool $allowDotEndOfLocalPart ローカルパートの末尾に.を許可するかどうか
     * @return bool 検証結果
     * @throws \Exception
     */
    public static function validateLocalPart($value, bool $allowDotEndOfLocalPart = false): bool
    {
        $stringValue = (string)$value;
        $pattern = (!$allowDotEndOfLocalPart)
            ? '(?:[-!#-\'*+\/-9=?A-Z^-~]+(?:\.[-!#-\'*+\/-9=?A-Z^-~]+)*|"(?:[!#-\[\]-~]|\\\\[\x09 -~])*")'
            : '(?:[-!#-\'*+\/-9=?A-Z^-~]+(?:\.[-!#-\'*+\/-9=?A-Z^-~]+|\.)*|"(?:[!#-\[\]-~]|\\\\[\x09 -~]|\.)*")';
        if ((false !== strpos($stringValue, '..')) ||
            !preg_match(sprintf('/\A%s\z/i', $pattern), $stringValue)) {
            throw new EmailException(
                'The value is invalid local-part.',
                EmailException::INVALID_LOCAL_PART);
        }

        try {
            Util::checkMaxLength($value, 64, parent::LENGTH_BYTES);
        } catch (MaxLengthException $e) {
            throw new MaxLengthException(
                'A local-part of the e-mail address is limited within 64 bytes.');
        } catch (\Exception $e) {
            throw $e;
        }
        return true;
    }

    /**
     * メールアドレスのドメインを検証します。
     *
     * @param mixed $value 検証値 (文字列または__toStringメソッド実装オブジェクト)
     * @return bool 検証結果
     * @throws \Exception
     */
    public static function validateDomain($value): bool
    {
        $stringValue = (string)$value;
        $pattern = '[-!#-\'*+\/-9=?A-Z^-~]+(?:\.[-!#-\'*+\/-9=?A-Z^-~]+)*';
        if (!preg_match(sprintf('/\A%s\z/i', $pattern), $stringValue)) {
            throw new EmailException(
                'The value is invalid domain.',
                EmailException::INVALID_DOMAIN);
        }

        try {
            Util::checkMaxLength($value, 255, parent::LENGTH_BYTES);
        } catch (MaxLengthException $e) {
            throw new MaxLengthException(
                'A domain of the e-mail address is limited within 255 bytes.');
        } catch (\Exception $e) {
            throw $e;
        }
        return true;
    }

}
