<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Auth;

interface AuthClientInterface
{

    /**
     * @param string $rawToken
     *
     * @return string
     */
    public function generateToken($rawToken);

    /**
     * @param string $rawToken
     * @param string $token
     *
     * @return bool
     */
    public function checkToken($rawToken, $token);

}
