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

class SecurityTokenUpdater implements SecurityTokenUpdaterInterface
{
    /**
     * @uses \Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\MerchantUserSecurityPlugin::SECURITY_FIREWALL_NAME
     */
    protected const SECURITY_FIREWALL_NAME = 'MerchantUser';

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    protected $tokenStorageService;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorageService
     */
    public function __construct(TokenStorageInterface $tokenStorageService)
    {
        $this->tokenStorageService = $tokenStorageService;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function update(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer
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
            [SecurityMerchantPortalGuiConfig::ROLE_MERCHANT_USER]
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
            $merchantUser->getPassword(),
            static::SECURITY_FIREWALL_NAME,
            [SecurityMerchantPortalGuiConfig::ROLE_MERCHANT_USER]
        );
    }
}
