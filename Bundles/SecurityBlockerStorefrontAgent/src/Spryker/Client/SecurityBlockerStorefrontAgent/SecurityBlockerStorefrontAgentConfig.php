<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerStorefrontAgent;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\SecurityBlockerStorefrontAgent\SecurityBlockerStorefrontAgentConstants;

class SecurityBlockerStorefrontAgentConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const SECURITY_BLOCKER_AGENT_ENTITY_TYPE = 'agent';

    /**
     * Specification:
     * - Returns security blocker agent entity type.
     *
     * @api
     *
     * @return string
     */
    public function getSecurityBlockerAgentEntityType(): string
    {
        return static::SECURITY_BLOCKER_AGENT_ENTITY_TYPE;
    }

    /**
     * Specification:
     * - Returns Customer time for block in seconds.
     *
     * @api
     *
     * @return int
     */
    public function getAgentBlockForSeconds(): int
    {
        return $this->get(SecurityBlockerStorefrontAgentConstants::AGENT_BLOCK_FOR_SECONDS, 0);
    }

    /**
     * Specification:
     * - Returns Agent blocking TTL in seconds.
     *
     * @api
     *
     * @return int
     */
    public function getAgentBlockingTTL(): int
    {
        return $this->get(SecurityBlockerStorefrontAgentConstants::AGENT_BLOCKING_TTL, 0);
    }

    /**
     * Specification:
     * - Returns Agent number of attempts configuration.
     *
     * @api
     *
     * @return int
     */
    public function getAgentBlockingNumberOfAttempts(): int
    {
        return $this->get(SecurityBlockerStorefrontAgentConstants::AGENT_BLOCKING_NUMBER_OF_ATTEMPTS, 0);
    }
}
