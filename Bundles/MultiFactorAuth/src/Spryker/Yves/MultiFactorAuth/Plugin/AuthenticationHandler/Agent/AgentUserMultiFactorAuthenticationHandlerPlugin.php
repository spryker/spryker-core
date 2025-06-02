<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Plugin\AuthenticationHandler\Agent;

use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\AgentPageExtension\Dependency\Plugin\AuthenticationHandlerPluginInterface;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 * @method \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface getClient()
 */
class AgentUserMultiFactorAuthenticationHandlerPlugin extends AbstractPlugin implements AuthenticationHandlerPluginInterface
{
    /**
     * @var string
     */
    protected const AGENT_USER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME = 'AGENT_USER_MULTI_FACTOR_AUTHENTICATION';

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
        return $entity === static::AGENT_USER_MULTI_FACTOR_AUTHENTICATION_HANDLER_NAME;
    }

    /**
     * {@inheritDoc}
     * - Validates whether the multi-factor authentication method is enabled for the provided agent user during login.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validateAgentMultiFactorStatus(
        MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
    ): MultiFactorAuthValidationResponseTransfer {
        return $this->getClient()->validateAgentMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer);
    }
}
