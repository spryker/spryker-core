<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecuritySystemUser\Communication;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SecuritySystemUser\Communication\Plugin\Security\Provider\SystemUserProvider;
use Spryker\Zed\SecuritySystemUser\Communication\Security\SystemUser;
use Spryker\Zed\SecuritySystemUser\Communication\Security\SystemUserInterface;
use Spryker\Zed\SecuritySystemUser\Dependency\Facade\SecuritySystemUserToUserFacadeInterface;
use Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig;
use Spryker\Zed\SecuritySystemUser\SecuritySystemUserDependencyProvider;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method \Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig getConfig()
 * @method \Spryker\Zed\SecuritySystemUser\Business\SecuritySystemUserFacadeInterface getFacade()
 */
class SecuritySystemUserCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    public function createSystemUserProvider(): UserProviderInterface
    {
        return new SystemUserProvider();
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Spryker\Zed\SecuritySystemUser\Communication\Security\SystemUserInterface
     */
    public function createSecurityUser(UserTransfer $userTransfer): SystemUserInterface
    {
        return new SystemUser(
            $userTransfer,
            [SecuritySystemUserConfig::ROLE_SYSTEM_USER]
        );
    }

    /**
     * @return \Spryker\Zed\SecuritySystemUser\Dependency\Facade\SecuritySystemUserToUserFacadeInterface
     */
    public function getUserFacade(): SecuritySystemUserToUserFacadeInterface
    {
        return $this->getProvidedDependency(SecuritySystemUserDependencyProvider::FACADE_USER);
    }
}
