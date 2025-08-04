<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Controller;

use Generated\Shared\Transfer\UserTransfer;

/**
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\MultiFactorAuthMerchantPortalCommunicationFactory getFactory()
 */
class AgentMerchantUserController extends MerchantUserController
{
    /**
     * @uses {@link \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\MultiFactorAuth\PostAgentMerchantUserLoginMultiFactorAuthenticationPlugin::AGENT_MERCHANT_USER_POST_AUTHENTICATION_TYPE}
     *
     * @var string
     */
    protected const AGENT_MERCHANT_USER_POST_AUTHENTICATION_TYPE = 'AGENT_MERCHANT_USER_POST_AUTHENTICATION_TYPE';

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    protected function executePostLoginMultiFactorAuthenticationPlugins(UserTransfer $userTransfer): void
    {
        foreach ($this->getFactory()->getPostLoginMultiFactorAuthenticationPlugins() as $plugin) {
            if ($plugin->isApplicable(static::AGENT_MERCHANT_USER_POST_AUTHENTICATION_TYPE) === false) {
                continue;
            }

            $plugin->createToken($userTransfer->getUsernameOrFail());
            $plugin->executeOnAuthenticationSuccess($userTransfer);
        }
    }

    /**
     * @return string
     */
    protected function getGetEnabledTypesTemplatePath(): string
    {
        return '@MultiFactorAuthMerchantPortal/AgentMerchantUser/get-enabled-types.twig';
    }

    /**
     * @return string
     */
    protected function getSendCodeTemplatePath(): string
    {
        return '@MultiFactorAuthMerchantPortal/AgentMerchantUser/send-code.twig';
    }
}
