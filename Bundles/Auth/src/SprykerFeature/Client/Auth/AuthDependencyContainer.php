<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Auth;

use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Auth\Token\TokenService;

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
