<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class AgentAuthRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_AGENT_CUSTOMER_IMPERSONATION_ACCESS_TOKENS = 'agent-customer-impersonation-access-tokens';
    public const RESOURCE_AGENT_ACCESS_TOKENS = 'agent-access-tokens';

    /**
     * @uses \Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig::GRANT_TYPE_AGENT_CREDENTIALS
     */
    public const GRANT_TYPE_AGENT_CREDENTIALS = 'agent_credentials';

    public const RESPONSE_CODE_INVALID_LOGIN = '4001';
    public const RESPONSE_DETAIL_INVALID_LOGIN = 'Failed to authenticate user.';

    public const RESPONSE_CODE_AGENT_ONLY = '4002';
    public const RESPONSE_DETAIL_AGENT_ONLY = 'Action is available to agent user only.';
}
