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
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
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
     * @param string $username
     *
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername(string $username)
    {
        $userTransfer = $this->resolveOauthUserByName($username);

        if ($userTransfer === null) {
            throw new UsernameNotFoundException();
        }

        return $this->getFactory()->createSecurityOauthUser($userTransfer);
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
        if (!$user instanceof SecurityOauthUser) {
            return $user;
        }

        $userTransfer = $this->resolveOauthUserByName($user->getUsername());

        if ($userTransfer === null) {
            throw new UsernameNotFoundException();
        }

        return $this->getFactory()->createSecurityOauthUser($userTransfer);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass(string $class)
    {
        return is_a($class, SecurityOauthUser::class, true);
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function resolveOauthUserByName(string $username): ?UserTransfer
    {
        $userTransfer = $this->getFacade()->resolveOauthUser(
            (new UserCriteriaTransfer())->setEmail($username)
        );

        if (!$userTransfer) {
            return null;
        }

        $oauthUserRestrictionResponseTransfer = $this->getFacade()->isOauthUserRestricted(
            (new OauthUserRestrictionRequestTransfer())->setUser($userTransfer)
        );

        if ($oauthUserRestrictionResponseTransfer->getIsRestricted()) {
            $this->addErrorMessages($oauthUserRestrictionResponseTransfer);

            return null;
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
        foreach ($oauthUserRestrictionResponseTransfer->getMessages() as $messageTransfer) {
            $this->getFactory()->getMessengerFacade()->addErrorMessage($messageTransfer);
        }
    }
}
