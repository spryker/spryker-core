<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\Agent\AgentConstants;

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
     * @return string[]
     */
    public function getAgentAllowedSecuredPatternList(): array
    {
        return $this->get(
            AgentConstants::AGENT_ALLOWED_SECURED_PATTERN_LIST,
            static::DEFAULT_AGENT_ALLOWED_SECURED_PATTERN_LIST
        );
    }
}
