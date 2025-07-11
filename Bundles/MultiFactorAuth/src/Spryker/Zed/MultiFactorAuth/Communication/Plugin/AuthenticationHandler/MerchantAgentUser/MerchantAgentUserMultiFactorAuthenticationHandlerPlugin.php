<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Plugin\AuthenticationHandler\MerchantAgentUser;

use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\AuthenticationHandlerPluginInterface;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class MerchantAgentUserMultiFactorAuthenticationHandlerPlugin extends AbstractPlugin implements AuthenticationHandlerPluginInterface
{
    /**
     * @var string
     */
    protected const MERCHANT_AGENT_USER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME = 'MERCHANT_AGENT_USER_MULTI_FACTOR_AUTHENTICATION';

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
        return $entity === static::MERCHANT_AGENT_USER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME;
    }

    /**
     * {@inheritDoc}
     * - Validates whether the multi-factor authentication method is enabled for the provided merchant agent user during login.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateMerchantUserMultiFactorStatus(
        MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
    ): MultiFactorAuthValidationResponseTransfer {
        return $this->getFacade()->validateUserMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer);
    }
}
