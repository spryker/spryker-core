<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AgentAuthRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class AgentAuthRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_AGENT_CUSTOMER_IMPERSONATION_ACCESS_TOKENS = 'agent-customer-impersonation-access-tokens';

    /**
     * @var string
     */
    public const RESOURCE_AGENT_ACCESS_TOKENS = 'agent-access-tokens';

    /**
     * @var string
     */
    public const RESOURCE_AGENT_CUSTOMER_SEARCH = 'agent-customer-search';

    /**
     * @uses \Spryker\Zed\OauthAgentConnector\OauthAgentConnectorConfig::GRANT_TYPE_AGENT_CREDENTIALS
     *
     * @var string
     */
    public const GRANT_TYPE_AGENT_CREDENTIALS = 'agent_credentials';

    /**
     * @uses \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig::GRANT_TYPE_CUSTOMER_IMPERSONATION
     *
     * @var string
     */
    public const GRANT_TYPE_CUSTOMER_IMPERSONATION = 'customer_impersonation';

    /**
     * @var string
     */
    public const HEADER_X_AGENT_AUTHORIZATION = 'X-Agent-Authorization';

    /**
     * @var string
     */
    public const RESPONSE_CODE_INVALID_LOGIN = '4101';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_INVALID_LOGIN = 'Failed to authenticate an agent.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_INVALID_ACCESS_TOKEN = '4102';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_INVALID_ACCESS_TOKEN = 'Agent access token is invalid.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_AGENT_ONLY = '4103';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_AGENT_ONLY = 'Action is available to agent user only.';

    /**
     * @var string
     */
    public const RESPONSE_CODE_FAILED_TO_IMPERSONATE_CUSTOMER = '4104';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_FAILED_TO_IMPERSONATE_CUSTOMER = 'Failed to impersonate a customer.';

    /**
     * @var int
     */
    protected const DEFAULT_CUSTOMER_SEARCH_PAGINATION_LIMIT = 10;

    /**
     * Specification:
     * - Returns resources which are accessible only for agents.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAgentResources(): array
    {
        return [
            static::RESOURCE_AGENT_CUSTOMER_IMPERSONATION_ACCESS_TOKENS,
            static::RESOURCE_AGENT_CUSTOMER_SEARCH,
        ];
    }

    /**
     * Specification:
     * - Returns the default pagination limit for customer search request.
     *
     * @api
     *
     * @return int
     */
    public function getDefaultCustomerSearchPaginationLimit(): int
    {
        return static::DEFAULT_CUSTOMER_SEARCH_PAGINATION_LIMIT;
    }
}
