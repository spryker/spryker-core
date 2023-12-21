<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Communication\Plugin\Security\Provider;

use Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityOauthUser\Communication\Security\SecurityOauthUser;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method \Spryker\Zed\SecurityOauthUser\Communication\SecurityOauthUserCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig getConfig()
 * @method \Spryker\Zed\SecurityOauthUser\Business\SecurityOauthUserFacadeInterface getFacade()
 */
class OauthUserProvider extends AbstractPlugin implements UserProviderInterface
{
    /**
     * @uses \Spryker\Zed\SecurityGui\Communication\Plugin\Security\UserSecurityPlugin::PATH_LOGIN_CHECK
     *
     * @var string
     */
    protected const PATH_LOGIN_CHECK = '/login_check';

    /**
     * {@inheritDoc}
     * - Loads the Oauth user for the given username.
     * - Excludes loading in case of form authentication check.
     *
     * @api
     *
     * @param string $username
     *
     * @throws \Symfony\Component\Security\Core\Exception\UserNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername($username)
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * @param string $identifier
     *
     * @throws \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @throws \Symfony\Component\Security\Core\Exception\UserNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        if (!$this->isLoadingApplicable()) {
            throw new UnsupportedUserException();
        }

        $userTransfer = $this->resolveOauthUserByName($identifier);

        if ($userTransfer === null) {
            throw $this->getUserNotFoundException();
        }

        return $this->getFactory()->createSecurityOauthUser($userTransfer);
    }

    /**
     * {@inheritDoc}
     * - Refreshes the Oauth user.
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
        if (!$user instanceof SecurityOauthUser) {
            return $user;
        }

        $userTransfer = $this->resolveOauthUserByName($user->getUsername());

        if ($userTransfer === null) {
            throw $this->getUserNotFoundException();
        }

        return $this->getFactory()->createSecurityOauthUser($userTransfer);
    }

    /**
     * {@inheritDoc}
     * - Checks support of the given user class.
     *
     * @api
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return is_a($class, SecurityOauthUser::class, true);
    }

    /**
     * @param string $username
     *
     * @throws \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function resolveOauthUserByName(string $username): ?UserTransfer
    {
        $userTransfer = $this->getFacade()->resolveOauthUser(
            (new UserCriteriaTransfer())->setEmail($username),
        );

        if (!$userTransfer) {
            return null;
        }

        $oauthUserRestrictionResponseTransfer = $this->getFacade()->isOauthUserRestricted(
            (new OauthUserRestrictionRequestTransfer())->setUser($userTransfer),
        );

        if ($oauthUserRestrictionResponseTransfer->getIsRestricted()) {
            $this->addErrorMessages($oauthUserRestrictionResponseTransfer);

            throw new UnsupportedUserException();
        }

        return $userTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer $oauthUserRestrictionResponseTransfer
     *
     * @return void
     */
    protected function addErrorMessages(
        OauthUserRestrictionResponseTransfer $oauthUserRestrictionResponseTransfer
    ): void {
        $messengerFacade = $this->getFactory()->getMessengerFacade();
        foreach ($oauthUserRestrictionResponseTransfer->getMessages() as $messageTransfer) {
            $messengerFacade->addErrorMessage($messageTransfer);
        }
    }

    /**
     * @return bool
     */
    protected function isLoadingApplicable(): bool
    {
        return $this->getFactory()->getRouter()->getContext()->getPathInfo() !== static::PATH_LOGIN_CHECK;
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
