<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityBlockerMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class AgentSecurityBlockerMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Client\AgentSecurityBlockerMerchantPortal\AgentSecurityBlockerMerchantPortalConfig::AGENT_MERCHANT_PORTAL_ENTITY_TYPE
     *
     * @var string
     */
    protected const SECURITY_BLOCKER_AGENT_MERCHANT_PORTAL_ENTITY_TYPE = 'agent-merchant-portal';

    /**
     * @see \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\AgentMerchantUserSecurityPlugin::extend().
     *
     * @var string
     */
    protected const AGENT_MERCHANT_PORTAL_LOGIN_CHECK_URL = 'agent-security-merchant-portal-gui_login_check';

    /**
     * Specification:
     * - Returns security blocker agent merchant portal entity type.
     *
     * @api
     *
     * @return string
     */
    public function getSecurityBlockerAgentMerchantPortalEntityType(): string
    {
        return static::SECURITY_BLOCKER_AGENT_MERCHANT_PORTAL_ENTITY_TYPE;
    }

    /**
     * Specification:
     * - Returns login check URL for an agent merchant portal.
     *
     * @api
     *
     * @return string
     */
    public function getAgentMerchantPortalLoginCheckUrl(): string
    {
        return static::AGENT_MERCHANT_PORTAL_LOGIN_CHECK_URL;
    }
}
