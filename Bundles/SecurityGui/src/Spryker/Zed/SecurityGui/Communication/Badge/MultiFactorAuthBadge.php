<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Badge;

use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class MultiFactorAuthBadge implements BadgeInterface
{
    /**
     * @uses \Spryker\Zed\MultiFactorAuth\Communication\Plugin\AuthenticationHandler\User\UserMultiFactorAuthenticationHandlerPlugin::USER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME
     *
     * @var string
     */
    protected const USER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME = 'USER_MULTI_FACTOR_AUTHENTICATION';

    /**
     * @var string
     */
    protected const PARAMETER_MULTI_FACTOR_AUTH_ENABLED = 'multi_factor_auth_enabled';

    /**
     * @uses \Spryker\Zed\SecurityGui\Communication\Form\LoginForm::FORM_NAME
     *
     * @var string
     */
    protected const PARAMETER_LOGIN_FORM = 'auth';

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
     * @param array<\Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\AuthenticationHandlerPluginInterface> $userMultiFactorAuthenticationHandlerPlugins
     */
    public function __construct(
        protected array $userMultiFactorAuthenticationHandlerPlugins
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return $this
     */
    public function enable(UserTransfer $userTransfer, Request $request)
    {
        foreach ($this->userMultiFactorAuthenticationHandlerPlugins as $plugin) {
            if ($plugin->isApplicable(static::USER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME) === false) {
                continue;
            }

            $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setUser($userTransfer);
            $multiFactorAuthValidationResponseTransfer = $plugin->validateUserMultiFactorStatus($multiFactorAuthValidationRequestTransfer);

            if ($multiFactorAuthValidationResponseTransfer->getIsRequired() === true && $this->isRequestCorrupted($request)) {
                $this->setIsResolved(false);

                return $this;
            }

            $this->setIsRequired($multiFactorAuthValidationResponseTransfer->getIsRequiredOrFail());
            $this->setStatus($multiFactorAuthValidationResponseTransfer->getStatus());
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isRequestCorrupted(Request $request): bool
    {
        return !$request->request->has(static::PARAMETER_MULTI_FACTOR_AUTH_ENABLED);
    }
}
