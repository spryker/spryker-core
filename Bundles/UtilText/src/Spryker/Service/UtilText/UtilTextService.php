<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilText\UtilTextServiceFactory getFactory()
 */
class UtilTextService extends AbstractService implements UtilTextServiceInterface
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
    public function generateSlug($value)
    {
        return $this->getFactory()
            ->createTextSlug()
            ->generate($value);
    }

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
    public function generateRandomString($length)
    {
        return $this->getFactory()
            ->createStringGenerator()
            ->generateRandomString($length);
    }

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
    public function hashValue($value, $algorithm)
    {
        return $this->getFactory()->createHash()->hashValue($value, $algorithm);
    }

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
    public function camelCaseToSeparator($string, $separator = '-')
    {
        return $this->getFactory()->createCamelCaseToSeparator()->filter($string, $separator);
    }

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
    public function separatorToCamelCase($string, $separator = '-', $upperCaseFirst = false)
    {
        return $this->getFactory()->createSeparatorToCamelCase()->filter($string, $separator, $upperCaseFirst);
    }

    /**
     * {@inheritdoc}
     */
    public function generateToken($rawToken, array $options = [])
    {
        return $this->getFactory()->createToken()->generate($rawToken, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function checkToken($rawToken, $hash)
    {
        return $this->getFactory()->createToken()->check($rawToken, $hash);
    }
}
