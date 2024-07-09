<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\DataProvider;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

class AuditLoggerUserProvider implements AuditLoggerUserProviderInterface
{
    /**
     * @uses \Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser
     *
     * @var string
     */
    protected const MERCHANT_USER_CLASS_NAME = '\Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser';

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    protected TokenStorageInterface $tokenStorage;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findOriginalUser(): ?UserTransfer
    {
        $token = $this->tokenStorage->getToken();

        if ($this->isPostAuthTokenForAgentMerchantUser($token)) {
            /**
             * @phpstan-var \Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken $token
             * @phpstan-var \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUser $agentMerchantUser
             */
            $agentMerchantUser = $token->getUser();

            return $agentMerchantUser->getUserTransfer();
        }

        if ($this->isSwitchUserTokenForMerchantUser($token)) {
            /**
             * @phpstan-var \Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken $token
             * @phpstan-var \Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser $merchantUser
             */
            $merchantUser = $token->getUser();

            return $merchantUser->getMerchantUserTransfer()->getUser();
        }

        return null;
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface|null $token
     *
     * @return bool
     */
    public function isPostAuthTokenForAgentMerchantUser(?TokenInterface $token): bool
    {
        return $token instanceof PostAuthenticationToken && $token->getUser() instanceof AgentMerchantUser;
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface|null $token
     *
     * @return bool
     */
    public function isSwitchUserTokenForMerchantUser(?TokenInterface $token): bool
    {
        $merchantUserClassName = static::MERCHANT_USER_CLASS_NAME;

        return $token instanceof SwitchUserToken && $token->getUser() instanceof $merchantUserClassName;
    }
}
