<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Auth\Token;

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
