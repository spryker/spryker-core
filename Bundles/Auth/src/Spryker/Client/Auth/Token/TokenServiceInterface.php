<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Auth\Token;

interface TokenServiceInterface
{

    /**
     * @param string $rawToken
     *
     * @return string
     */
    public function generate($rawToken);

    /**
     * @param string $rawToken
     * @param string $token
     *
     * @return bool
     */
    public function check($rawToken, $token);

}
