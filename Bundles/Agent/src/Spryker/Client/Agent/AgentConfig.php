<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\Agent\AgentConstants;

/**
 * @method \Spryker\Shared\Agent\AgentConfig getSharedConfig()
 */
class AgentConfig extends AbstractBundleConfig
{
    /**
     * @var array
     */
    protected const DEFAULT_AGENT_ALLOWED_SECURED_PATTERN_LIST = [];

    /**
     * Specification:
     * - Gets a list of secured patterns that are allowed for an agent.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAgentAllowedSecuredPatternList(): array
    {
        return $this->get(
            AgentConstants::AGENT_ALLOWED_SECURED_PATTERN_LIST,
            static::DEFAULT_AGENT_ALLOWED_SECURED_PATTERN_LIST,
        );
    }

    /**
     * Specification:
     * - Enable or disable agent info capturing in the orders when agent assists with order placing.
     *
     * @api
     *
     * @return bool
     */
    public function isSalesOrderAgentEnabled(): bool
    {
        return $this->getSharedConfig()->isSalesOrderAgentEnabled();
    }
}
