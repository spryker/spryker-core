<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Auth\Service\Token;

interface TokenServiceInterface
{

    /**
     * @param $rawToken
     *
     * @return string
     */
    public function generate($rawToken);

    /**
     * @param $rawToken
     * @param $token
     *
     * @return bool
     */
    public function check($rawToken, $token);

}
