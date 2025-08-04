<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Controller;

/**
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\MultiFactorAuthMerchantPortalCommunicationFactory getFactory()
 */
class AgentUserManagementController extends UserManagementController
{
    /**
     * @var string
     */
    protected const URL_REDIRECT_SET_UP_PAGE = '/multi-factor-auth-merchant-portal/agent-user-management/set-up';

    /**
     * @uses \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig::PATH_LOGIN
     *
     * @var string
     */
    protected const LOGIN_PATH = '/agent-security-merchant-portal-gui/login';

    /**
     * @return string
     */
    protected function getSetUpTemplatePath(): string
    {
        return '@MultiFactorAuthMerchantPortal/UserManagement/set-up-agent-merchant-portal.twig';
    }
}
