<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Provider;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Exception\AccessDeniedException;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig getConfig()
 */
class MerchantUserProvider extends AbstractPlugin implements UserProviderInterface
{
    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @var array<\Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserLoginRestrictionPluginInterface>
     */
    protected array $merchantUserLoginRestrictionPlugins;

    /**
     * @param array<\Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserLoginRestrictionPluginInterface> $merchantUserLoginRestrictionPlugins
     */
    public function __construct(array $merchantUserLoginRestrictionPlugins)
    {
        $this->merchantUserLoginRestrictionPlugins = $merchantUserLoginRestrictionPlugins;
    }

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
     * @throws \Spryker\Zed\SecurityMerchantPortalGui\Communication\Exception\AccessDeniedException
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @throws \Symfony\Component\Security\Core\Exception\UserNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $merchantUserTransfer = $this->findMerchantUser($identifier);

        if ($merchantUserTransfer === null) {
            throw $this->getUserNotFoundException();
        }

        foreach ($this->merchantUserLoginRestrictionPlugins as $merchantUserLoginRestrictionPlugin) {
            if ($merchantUserLoginRestrictionPlugin->isRestricted($merchantUserTransfer)) {
                throw new AccessDeniedException();
            }
        }

        return $this->getFactory()->createSecurityUser($merchantUserTransfer);
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @throws \Symfony\Component\Security\Core\Exception\UserNotFoundException
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof MerchantUser) {
            return $user;
        }

        $merchantUserTransfer = $this->findMerchantUser($user->getUsername());

        if ($merchantUserTransfer === null) {
            throw $this->getUserNotFoundException();
        }

        return $this->getFactory()->createSecurityUser($merchantUserTransfer);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass(string $class): bool
    {
        return is_a($class, MerchantUser::class, true);
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
                    ->setStatus('active')
                    ->setMerchantStatus(static::MERCHANT_STATUS_APPROVED),
            );
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
