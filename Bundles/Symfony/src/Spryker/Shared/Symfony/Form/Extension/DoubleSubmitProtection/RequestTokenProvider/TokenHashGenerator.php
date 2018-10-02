<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider;

class TokenHashGenerator implements TokenGeneratorInterface
{
    /**
     * @var string $algorithm
     */
    protected $algorithm;

    /**
     * @param string $algorithm
     */
    public function __construct($algorithm = self::DEFAULT_ALGORITHM)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * Generates and returns random token
     *
     * @return string
     */
    public function generateToken()
    {
        return hash($this->algorithm, microtime() . mt_rand());
    }

    /**
     * @param mixed $expected
     * @param mixed $actual
     *
     * @return bool
     */
    public function checkTokenEquals($expected, $actual)
    {
        return hash_equals($expected, $actual);
    }
}
