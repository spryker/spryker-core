<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Badge;

use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class MultiFactorAuthBadge implements BadgeInterface
{
    /**
     * @uses \Spryker\Zed\MultiFactorAuth\Communication\Plugin\AuthenticationHandler\MerchantAgentUser\MerchantAgentUserMultiFactorAuthenticationHandlerPlugin::MERCHANT_AGENT_USER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME
     *
     * @var string
     */
    protected const MERCHANT_AGENT_USER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME = 'MERCHANT_AGENT_USER_MULTI_FACTOR_AUTHENTICATION';

    /**
     * @var bool
     */
    protected bool $isRequired = false;

    /**
     * @var bool
     */
    protected bool $isResolved = true;

    /**
     * @var int|null
     */
    protected ?int $status = null;

    /**
     * @param array<\Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\AuthenticationHandlerPluginInterface> $merchantAgentUserMultiFactorAuthenticationHandlerPlugins
     */
    public function __construct(
        protected array $merchantAgentUserMultiFactorAuthenticationHandlerPlugins
    ) {
    }

    /**
     * @return bool
     */
    public function isResolved(): bool
    {
        return $this->isResolved;
    }

    /**
     * @param bool $isResolved
     *
     * @return void
     */
    public function setIsResolved(bool $isResolved): void
    {
        $this->isResolved = $isResolved;
    }

    /**
     * @param bool $isRequired
     *
     * @return void
     */
    public function setIsRequired(bool $isRequired): void
    {
        $this->isRequired = $isRequired;
    }

    /**
     * @return bool
     */
    public function getIsRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @param int|null $status
     *
     * @return void
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return $this
     */
    public function enable(UserTransfer $userTransfer)
    {
        foreach ($this->merchantAgentUserMultiFactorAuthenticationHandlerPlugins as $plugin) {
            if ($plugin->isApplicable(static::MERCHANT_AGENT_USER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME) === false) {
                continue;
            }

            $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setUser($userTransfer);
            $multiFactorAuthValidationResponseTransfer = $plugin->validateMerchantUserMultiFactorStatus($multiFactorAuthValidationRequestTransfer);

            $this->setIsRequired($multiFactorAuthValidationResponseTransfer->getIsRequiredOrFail());
            $this->setStatus($multiFactorAuthValidationResponseTransfer->getStatus());
        }

        return $this;
    }
}
