<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Plugin\AuthenticationHandler\User;

use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\AuthenticationHandlerPluginInterface;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class UserMultiFactorAuthenticationHandlerPlugin extends AbstractPlugin implements AuthenticationHandlerPluginInterface
{
    /**
     * @var string
     */
    protected const USER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME = 'USER_MULTI_FACTOR_AUTHENTICATION';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $entity
     *
     * @return bool
     */
    public function isApplicable(string $entity): bool
    {
        return $entity === static::USER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME;
    }

    /**
     * {@inheritDoc}
     * - Validates whether the multi-factor authentication method is enabled for the provided user during login.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateUserMultiFactorStatus(
        MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
    ): MultiFactorAuthValidationResponseTransfer {
        return $this->getFacade()->validateUserMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer);
    }
}
