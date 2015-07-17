<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Auth\Client;

abstract class StaticToken
{

    /**
     * @var string
     */
    protected $rawToken = null;

    /**
     * @param string $token
     */
    public function setRawToken($token)
    {
        $this->rawToken = $token;
    }

    /**
     * @return string
     */
    public function getRawToken()
    {
        return $this->rawToken;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return base64_encode(password_hash($this->rawToken, PASSWORD_DEFAULT));
    }

    /**
     * @param string $hash
     *
     * @return bool
     */
    public function check($hash)
    {
        return password_verify($this->rawToken, base64_decode($hash));
    }

}
