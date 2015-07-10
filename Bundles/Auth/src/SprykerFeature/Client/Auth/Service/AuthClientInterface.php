<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Auth\Service;

interface AuthClientInterface
{

    /**
     * @param $rawToken
     *
     * @return string
     */
    public function generateToken($rawToken);

    /**
     * @param $rawToken
     * @param $token
     *
     * @return bool
     */
    public function checkToken($rawToken, $token);

}
