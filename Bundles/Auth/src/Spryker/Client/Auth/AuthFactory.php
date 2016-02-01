<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Auth;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Auth\Token\TokenService;

class AuthFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Auth\Token\TokenService
     */
    public function createTokenService()
    {
        return new TokenService();
    }

}
