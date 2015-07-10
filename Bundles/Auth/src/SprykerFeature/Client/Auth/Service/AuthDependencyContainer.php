<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Auth\Service;

use Generated\Client\Ide\FactoryAutoCompletion\AuthService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Auth\Service\Token\TokenService;

/**
 * @method AuthService getFactory()
 */
class AuthDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return TokenService
     */
    public function createTokenService()
    {
        return $this->getFactory()->createTokenTokenService();
    }

}
