<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class SecurityBlockerRestApiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Glue\AuthRestApi\AuthRestApiConfig::RESOURCE_ACCESS_TOKENS
     */
    public const RESOURCE_ACCESS_TOKENS = 'access-tokens';

    /**
     * @uses \Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig::RESOURCE_AGENT_ACCESS_TOKENS
     */
    public const RESOURCE_AGENT_ACCESS_TOKENS = 'agent-access-tokens';

    /**
     * @uses \SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageConfig::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE
     */
    public const SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE = 'customer';

    /**
     * @uses \SprykerShop\Yves\SecurityBlockerPage\SecurityBlockerPageConfig::SECURITY_BLOCKER_AGENT_ENTITY_TYPE
     */
    public const SECURITY_BLOCKER_AGENT_ENTITY_TYPE = 'agent';

    public const ERROR_RESPONSE_CODE_ACCOUNT_BLOCKED = '4401';
    public const ERROR_RESPONSE_DETAIL_ACCOUNT_BLOCKED = 'security_blocker_page.error.account_blocked';
}
