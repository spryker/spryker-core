<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecuritySystemUser\Communication\Plugin\Security\Provider;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecuritySystemUser\Communication\Security\SystemUser;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method \Spryker\Zed\SecuritySystemUser\Communication\SecuritySystemUserCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig getConfig()
 * @method \Spryker\Zed\SecuritySystemUser\Business\SecuritySystemUserFacadeInterface getFacade()
 */
class SystemUserProvider extends AbstractPlugin implements UserProviderInterface
{
    /**
     * @param string $token
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername($token)
    {
        return $this->getUserByToken($token);
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof SystemUser || !$user->getUsername()) {
            return $user;
        }

        /** @var string $username */
        $username = $user->getUsername();

        return $this->getUserByUsername($username);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return is_a($class, SystemUser::class, true);
    }

    /**
     * @param string $token
     *
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    protected function getUserByToken(string $token): UserInterface
    {
        foreach ($this->getConfig()->getUsersCredentials() as $username => $credential) {
            if (!$credential['token']) {
                continue;
            }

            if ($this->isValidToken($credential['token'], $token)) {
                return $this->getFactory()->createSecurityUser(
                    (new UserTransfer())->setUsername($username)
                        ->setPassword($credential['token'])
                );
            }
        }

        throw new UsernameNotFoundException();
    }

    /**
     * @param string $username
     *
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    protected function getUserByUsername(string $username): UserInterface
    {
        foreach ($this->getConfig()->getUsersCredentials() as $securityUserName => $credential) {
            if ($securityUserName === $username) {
                return $this->getFactory()->createSecurityUser(
                    (new UserTransfer())->setUsername($username)
                        ->setPassword($credential['token'])
                );
            }
        }

        throw new UsernameNotFoundException();
    }

    /**
     * @param string $userToken
     * @param string $token
     *
     * @return bool
     */
    protected function isValidToken(string $userToken, string $token): bool
    {
        return password_verify($userToken, base64_decode($token));
    }
}
