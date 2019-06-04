<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Service\UtilText;

interface UtilTextServiceInterface
{
    /**
     * Specification:
     * - Generate slug based on value.
     *
     * @api
     *
     * @param string $value
     *
     * @return string
     */
    public function generateSlug($value);

    /**
     * Specification:
     * - Generates random string for given length value.
     *
     * @api
     *
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($length);

    /**
     * Specification:
     * - Generates hash from value by specified algorithm.
     *
     * @api
     *
     * @param mixed $value
     * @param string $algorithm
     *
     * @return string
     */
    public function hashValue($value, $algorithm);

    /**
     * Specification:
     * - Converts a camel cased string into a string where every word is linked with the other by specified separator.
     *
     * @api
     *
     * @param string $string
     * @param string $separator
     *
     * @return string
     */
    public function camelCaseToSeparator($string, $separator = '-');

    /**
     * Specification:
     * - Converts a string with a given separator into a camel cased string.
     *
     * @api
     *
     * @param string $string
     * @param string $separator
     * @param bool $upperCaseFirst
     *
     * @return string
     */
    public function separatorToCamelCase($string, $separator = '-', $upperCaseFirst = false);

    /**
     * Specification:
     * - Generates a token from given string.
     *
     * @api
     *
     * @param string $rawToken
     * @param array $options
     *
     * @return string
     */
    public function generateToken($rawToken, array $options = []);

    /**
     * Specification:
     * - Checks if a given token is valid.
     *
     * @param string $rawToken
     * @param string $hash
     *
     * @return bool
     */
    public function checkToken($rawToken, $hash);
}
