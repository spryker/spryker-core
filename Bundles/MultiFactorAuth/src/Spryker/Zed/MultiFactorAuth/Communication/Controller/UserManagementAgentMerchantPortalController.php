<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Controller;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 */
class UserManagementAgentMerchantPortalController extends UserManagementController
{
    /**
     * @var string
     */
    protected const URL_REDIRECT_SET_UP_PAGE = '/multi-factor-auth/user-management-agent-merchant-portal/set-up';

    /**
     * @uses \\Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig::PATH_LOGIN
     *
     * @var string
     */
    protected const LOGIN_PATH = '/agent-security-merchant-portal-gui/login';

    /**
     * @return string
     */
    protected function getSetUpTemplatePath(): string
    {
        return '@MultiFactorAuth/UserManagement/set-up-agent-merchant-portal.twig';
    }
}
