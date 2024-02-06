<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Updater;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser;
use Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurityTokenUpdater implements SecurityTokenUpdaterInterface
{
    /**
     * @uses \Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\MerchantUserSecurityPlugin::SECURITY_FIREWALL_NAME
     *
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'MerchantUser';

    /**
     * @uses \Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter::IS_IMPERSONATOR
     *
     * @var string
     */
    protected const IS_IMPERSONATOR = 'IS_IMPERSONATOR';

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected TokenStorageInterface $tokenStorageService;

    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    protected AuthorizationCheckerInterface $authorizationChecker;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorageService
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(TokenStorageInterface $tokenStorageService, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->tokenStorageService = $tokenStorageService;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function updateMerchantUserToken(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer
    {
        if (
            $this->authorizationChecker->isGranted(SecurityMerchantPortalGuiConfig::ROLE_MERCHANT_USER)
            && !$this->authorizationChecker->isGranted(static::IS_IMPERSONATOR)
        ) {
            $merchantUserTransfer = $this->setToken($merchantUserTransfer);
        }

        return $merchantUserTransfer;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\SecurityMerchantPortalGui\Communication\Updater\SecurityTokenUpdater::updateMerchantUserToken()} instead.
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function update(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer
    {
        return $this->setToken($merchantUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected function setToken(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer
    {
        $merchantUser = $this->createMerchantUser($merchantUserTransfer);
        $token = $this->createNewToken($merchantUser);

        $this->tokenStorageService->setToken($token);

        return $merchantUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser
     */
    protected function createMerchantUser(MerchantUserTransfer $merchantUserTransfer): MerchantUser
    {
        return new MerchantUser(
            $merchantUserTransfer,
            [SecurityMerchantPortalGuiConfig::ROLE_MERCHANT_USER],
        );
    }

    /**
     * @param \Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser $merchantUser
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken
     */
    protected function createNewToken(MerchantUser $merchantUser): UsernamePasswordToken
    {
        return new UsernamePasswordToken(
            $merchantUser,
            static::SECURITY_FIREWALL_NAME,
            [SecurityMerchantPortalGuiConfig::ROLE_MERCHANT_USER],
        );
    }
}
