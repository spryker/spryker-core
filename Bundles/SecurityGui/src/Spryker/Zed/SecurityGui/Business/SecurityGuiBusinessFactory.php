<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SecurityGui\Business\Authenticator\UserAuthenticator;
use Spryker\Zed\SecurityGui\Business\Authenticator\UserAuthenticatorInterface;
use Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToUserFacadeInterface;
use Spryker\Zed\SecurityGui\SecurityGuiDependencyProvider;

/**
 * @method \Spryker\Zed\SecurityGui\SecurityGuiConfig getConfig()
 */
class SecurityGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SecurityGui\Business\Authenticator\UserAuthenticatorInterface
     */
    public function createUserAuthenticator(): UserAuthenticatorInterface
    {
        return new UserAuthenticator($this->getUserFacade());
    }

    /**
     * @return \Spryker\Zed\SecurityGui\Dependency\Facade\SecurityGuiToUserFacadeInterface
     */
    public function getUserFacade(): SecurityGuiToUserFacadeInterface
    {
        return $this->getProvidedDependency(SecurityGuiDependencyProvider::FACADE_USER);
    }
}
