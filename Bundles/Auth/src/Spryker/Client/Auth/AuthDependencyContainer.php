<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Auth;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Auth\Token\TokenService;

class AuthDependencyContainer extends AbstractFactory
{

    /**
     * @return TokenService
     */
    public function createTokenService()
    {
        return new TokenService();
    }

}
