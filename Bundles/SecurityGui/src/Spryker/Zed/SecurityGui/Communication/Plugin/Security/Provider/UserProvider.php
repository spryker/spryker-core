<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Plugin\Security\Provider;

use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityGui\Communication\Exception\AccessDeniedException;
use Spryker\Zed\SecurityGui\Communication\Security\User;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
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
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     *
     * @var string
     */
    protected const USER_STATUS_ACTIVE = 'active';

    /**
     * @var array<\Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\UserRoleFilterPluginInterface>
     */
    protected $userRoleFilterPlugins;

    /**
     * @var array<\Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\UserLoginRestrictionPluginInterface>
     */
    protected array $userLoginRestrictionPlugins;

    /**
     * @param array<\Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\UserRoleFilterPluginInterface> $userRoleFilterPlugins
     * @param array<\Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\UserLoginRestrictionPluginInterface> $userLoginRestrictionPlugins
     */
    public function __construct(
        array $userRoleFilterPlugins,
        array $userLoginRestrictionPlugins
    ) {
        $this->userRoleFilterPlugins = $userRoleFilterPlugins;
        $this->userLoginRestrictionPlugins = $userLoginRestrictionPlugins;
    }

    /**
     * {@inheritDoc}
     * - Finds user in the persistence by provided username.
     * - Executes a stack of {@link \Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\UserLoginRestrictionPluginInterface} plugins.
     * - Throws {@link \Spryker\Zed\SecurityGui\Communication\Exception\AccessDeniedException} when user is restricted.
     *
     * @api
     *
     * @param string $username
     *
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername($username) /** @phpstan-ignore-line */
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * @param string $identifier
     *
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @throws \Symfony\Component\Security\Core\Exception\UserNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $userTransfer = $this->findUserByUsername($identifier);

        if ($userTransfer === null) {
            throw $this->getUserNotFoundException();
        }

        $this->executeUserLoginRestrictionPlugins($userTransfer);

        return $this->getFactory()
            ->createSecurityUser(
                $userTransfer,
                $this->getUserAuthenticationRoles($userTransfer),
            );
    }

    /**
     * {@inheritDoc}
     * - Finds user in the persistence by provided UserInterface.username.
     * - Executes a stack of {@link \Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\UserLoginRestrictionPluginInterface} plugins.
     * - Throws {@link \Spryker\Zed\SecurityGui\Communication\Exception\AccessDeniedException} when user is restricted.
     *
     * @api
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @throws \Symfony\Component\Security\Core\Exception\UserNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            return $user;
        }

        $userTransfer = $this->findUserByUsername($user->getUsername());

        if ($userTransfer === null) {
            throw $this->getUserNotFoundException();
        }

        $this->executeUserLoginRestrictionPlugins($userTransfer);

        return $this->getFactory()
            ->createSecurityUser(
                $userTransfer,
                $this->getUserAuthenticationRoles($userTransfer),
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
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

        $userCriteriaTransfer = $this->createUserCriteriaTransfer($username);
        $userCollectionTransfer = $this->getFactory()->getUserFacade()->getUserCollection($userCriteriaTransfer);

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return array<string>
     */
    protected function getUserAuthenticationRoles(UserTransfer $userTransfer): array
    {
        $roles = $this->getConfig()->getDefaultBackofficeAuthenticationRoles();

        foreach ($this->userRoleFilterPlugins as $roleFilterPlugin) {
            $roles = $roleFilterPlugin->filter($userTransfer, $roles);
        }

        return $roles;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @throws \Spryker\Zed\SecurityGui\Communication\Exception\AccessDeniedException
     *
     * @return void
     */
    protected function executeUserLoginRestrictionPlugins(UserTransfer $userTransfer): void
    {
        foreach ($this->userLoginRestrictionPlugins as $userLoginRestrictionPlugin) {
            if ($userLoginRestrictionPlugin->isRestricted($userTransfer)) {
                throw new AccessDeniedException();
            }
        }
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserCriteriaTransfer
     */
    protected function createUserCriteriaTransfer(string $username): UserCriteriaTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())
            ->addUsername($username)
            ->addStatus(static::USER_STATUS_ACTIVE);

        return (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);
    }

    /**
     * @return \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    protected function getUserNotFoundException(): AuthenticationException
    {
        if ($this->isSymfonyVersion5() === true) {
            /** @phpstan-ignore-next-line */
            return new UsernameNotFoundException();
        }

        return new UserNotFoundException();
    }

    /**
     * @deprecated Shim for Symfony Security Core 5.x, to be removed when Symfony Security Core dependency becomes 6.x+.
     *
     * @return bool
     */
    protected function isSymfonyVersion5(): bool
    {
        return class_exists(AuthenticationProviderManager::class);
    }
}
