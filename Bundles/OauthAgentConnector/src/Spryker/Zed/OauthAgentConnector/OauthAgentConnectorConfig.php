<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector;

use Spryker\Shared\OauthAgentConnector\OauthCustomerConnectorConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class OauthAgentConnectorConfig extends AbstractBundleConfig
{
    protected const OAUTH_SCOPE_AGENT = 'agent';

    public const GRANT_TYPE_AGENT_CREDENTIALS = 'agent_credentials';

    /**
     * Specification:
     * - Returns agent user scopes.
     *
     * @api
     *
     * @return string[]
     */
    public function getAgentScopes(): array
    {
        return [static::OAUTH_SCOPE_AGENT];
    }
}
