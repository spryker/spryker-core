<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Auth\Service;

use Generated\Client\Ide\FactoryAutoCompletion\AuthService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Auth\Service\Token\TokenService;

class AuthDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return TokenService
     */
    public function createTokenService()
    {
        return new TokenService();
    }

}
