<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Security\Business\Security\AuthorizationChecker;
use Spryker\Zed\Security\Business\Security\AuthorizationCheckerInterface;
use Spryker\Zed\Security\SecurityDependencyProvider;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface as SymfonyAuthorizationCheckerInterface;

/**
 * @method \Spryker\Zed\Security\SecurityConfig getConfig()
 */
class SecurityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Security\Business\Security\AuthorizationCheckerInterface
     */
    public function createSecurityAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return new AuthorizationChecker(
            $this->getAuthorizationCheckerService()
        );
    }

    /**
     * @return \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    public function getAuthorizationCheckerService(): SymfonyAuthorizationCheckerInterface
    {
        return $this->getProvidedDependency(SecurityDependencyProvider::SERVICE_SECURITY_AUTHORIZATION_CHECKER);
    }
}
