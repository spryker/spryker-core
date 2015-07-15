<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Auth\Service\Token;

class TokenService implements TokenServiceInterface
{

    /**
     * @param $rawToken
     *
     * @return string
     */
    public function generate($rawToken)
    {
        return base64_encode(password_hash($rawToken, PASSWORD_DEFAULT));
    }

    /**
     * @param $rawToken
     * @param $hash
     *
     * @return bool
     */
    public function check($rawToken, $hash)
    {
        return password_verify($rawToken, base64_decode($hash));
    }

}
