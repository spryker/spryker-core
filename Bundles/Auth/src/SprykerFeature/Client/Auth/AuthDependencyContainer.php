<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Auth;

use Spryker\Client\Kernel\AbstractDependencyContainer;
use Spryker\Client\Auth\Token\TokenService;

class AuthDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return TokenService
     */
    public function createTokenService()
    {
        return new TokenService();
    }

}
