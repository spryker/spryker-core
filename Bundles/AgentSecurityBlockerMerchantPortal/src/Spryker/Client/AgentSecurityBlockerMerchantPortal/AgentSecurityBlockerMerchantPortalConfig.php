<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentSecurityBlockerMerchantPortal;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\AgentSecurityBlockerMerchantPortal\AgentSecurityBlockerMerchantPortalConstants;

class AgentSecurityBlockerMerchantPortalConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const AGENT_MERCHANT_PORTAL_ENTITY_TYPE = 'agent-merchant-portal';

    /**
     * Specification:
     * - Returns security blocker agent merchant portal entity type.
     *
     * @api
     *
     * @return string
     */
    public function getAgentMerchantPortalSecurityBlockerEntityType(): string
    {
        return static::AGENT_MERCHANT_PORTAL_ENTITY_TYPE;
    }

    /**
     * Specification:
     * - Returns agent merchant portal time for remembering failed login attempts in seconds.
     *
     * @api
     *
     * @return int
     */
    public function getAgentMerchantPortalBlockingTTL(): int
    {
        return $this->get(AgentSecurityBlockerMerchantPortalConstants::AGENT_MERCHANT_PORTAL_BLOCKING_TTL, 0);
    }

    /**
     * Specification:
     * - Returns agent merchant portal block time in seconds.
     *
     * @api
     *
     * @return int
     */
    public function getAgentMerchantPortalBlockForSeconds(): int
    {
        return $this->get(AgentSecurityBlockerMerchantPortalConstants::AGENT_MERCHANT_PORTAL_BLOCK_FOR_SECONDS, 0);
    }

    /**
     * Specification:
     * - Returns agent merchant portal number of attempts configuration.
     *
     * @api
     *
     * @return int
     */
    public function getAgentMerchantPortalBlockingNumberOfAttempts(): int
    {
        return $this->get(AgentSecurityBlockerMerchantPortalConstants::AGENT_MERCHANT_PORTAL_BLOCKING_NUMBER_OF_ATTEMPTS, 0);
    }
}
