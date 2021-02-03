<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Plugin\Security\Provider;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityGui\Communication\Security\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method \Spryker\Zed\SecurityGui\Communication\SecurityGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityGui\Business\SecurityGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\SecurityGui\SecurityGuiConfig getConfig()
 */
class UserProvider extends AbstractPlugin implements UserProviderInterface
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
        $userTransfer = $this->findUserByUsername($username);

        if ($userTransfer === null) {
            throw new UsernameNotFoundException();
        }

        return $this->getFactory()
            ->createSecurityUser($userTransfer);
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

        $userTransfer = $this->findUserTransfer($user);

        if ($userTransfer === null) {
            throw new UsernameNotFoundException();
        }

        return $this->getFactory()->createSecurityUser($userTransfer);
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
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUserByUsername(string $username): ?UserTransfer
    {
        if (!$this->getFactory()->getUserFacade()->hasActiveUserByUsername($username)) {
            return null;
        }

        return $this->getFactory()
            ->getUserFacade()
            ->getUserByUsername($username);
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUserTransfer(UserInterface $user): ?UserTransfer
    {
        return $this->findUserByUsername($user->getUsername());
    }
}
