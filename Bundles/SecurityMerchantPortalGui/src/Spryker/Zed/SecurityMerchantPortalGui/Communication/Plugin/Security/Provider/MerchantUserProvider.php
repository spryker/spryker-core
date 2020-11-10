<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Provider;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig getConfig()
 */
class MerchantUserProvider extends AbstractPlugin implements UserProviderInterface
{
    /**
     * @param string $username
     *
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername($username)
    {
        $merchantUserTransfer = $this->findMerchantUser($username);

        if ($merchantUserTransfer === null) {
            throw new UsernameNotFoundException();
        }

        return $this->getFactory()
            ->createSecurityUser($merchantUserTransfer);
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            return $user;
        }

        $merchantUserTransfer = $this->findMerchantUser($user->getUsername());

        if ($merchantUserTransfer === null) {
            throw new UsernameNotFoundException();
        }

        return $this->getFactory()->createSecurityUser($merchantUserTransfer);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return is_a($class, User::class, true);
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    protected function findMerchantUser(string $username): ?MerchantUserTransfer
    {
        return $this->getFactory()
            ->getMerchantUserFacade()
            ->findMerchantUser(
                (new MerchantUserCriteriaTransfer())
                    ->setUsername($username)
                    ->setWithUser(true)
            );
    }
}
