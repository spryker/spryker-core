<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecuritySystemUser\Communication;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SecuritySystemUser\Communication\Plugin\Security\Provider\SystemUserProvider;
use Spryker\Zed\SecuritySystemUser\Communication\Security\User;
use Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method \Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig getConfig()
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
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function createSecurityUser(UserTransfer $userTransfer): UserInterface
    {
        return new User(
            $userTransfer,
            [SecuritySystemUserConfig::ROLE_SYSTEM_USER]
        );
    }
}
