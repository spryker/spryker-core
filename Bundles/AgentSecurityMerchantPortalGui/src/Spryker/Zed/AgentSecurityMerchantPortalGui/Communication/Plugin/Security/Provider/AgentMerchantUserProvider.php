<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Provider;

use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUser;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\AgentSecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig getConfig()
 */
class AgentMerchantUserProvider extends AbstractPlugin implements UserProviderInterface
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     *
     * @var string
     */
    protected const COL_STATUS_ACTIVE = 'active';

    /**
     * @param string $username
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername(string $username): UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * @param string $identifier
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->createSecurityUserByUsername($identifier);
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if ($user instanceof AgentMerchantUser) {
            return $this->refreshAgentMerchantUser($user);
        }

        if (is_a($user, $this->getConfig()->getMerchantUserClassName(), true)) {
            return $this->refreshMerchantUser($user);
        }

        return $user;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass(string $class): bool
    {
        return is_a($class, AgentMerchantUser::class, true)
            || is_a($class, $this->getConfig()->getMerchantUserClassName(), true);
    }

    /**
     * @param \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUser $user
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    protected function refreshAgentMerchantUser(AgentMerchantUser $user): UserInterface
    {
        return $this->createSecurityUserByUsername(
            $user->getUserTransfer()->getUsernameOrFail(),
        );
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    protected function refreshMerchantUser(UserInterface $user): UserInterface
    {
        /** @phpstan-var \Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser $user */
        $username = $user->getMerchantUserTransfer()->getAgentUsername();
        if (!$username) {
            return $user;
        }

        $userTransfer = $this->findUserByUsername($username);

        if ($userTransfer === null) {
            $this->throwUserNotFoundException();
        }

        return $user;
    }

    /**
     * @param string $username
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    protected function createSecurityUserByUsername(string $username): UserInterface
    {
        $userTransfer = $this->findUserByUsername($username);

        if ($userTransfer === null) {
            $this->throwUserNotFoundException();
        }

        /** @phpstan-var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        return $this->getFactory()->createSecurityUser($userTransfer);
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findUserByUsername(string $username): ?UserTransfer
    {
        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setUserConditions(
                (new UserConditionsTransfer())
                    ->addUsername($username)
                    ->addStatus(static::COL_STATUS_ACTIVE)
                    ->setIsMerchantAgent(true),
            );

        $userTransfers = $this->getFactory()
            ->getUserFacade()
            ->getUserCollection($userCriteriaTransfer)
            ->getUsers();

        return $userTransfers->getIterator()->current();
    }

    /**
     * @throws \Symfony\Component\Security\Core\Exception\UserNotFoundException
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     *
     * @return void
     */
    protected function throwUserNotFoundException(): void
    {
        if ($this->getFactory()->createSymfonyVersionChecker()->isSymfonyVersion5()) {
            throw new UsernameNotFoundException();
        }

        throw new UserNotFoundException();
    }
}
