<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Configurator;

use Spryker\Client\Agent\AgentConfig;

class AgentAccessConfigurator implements AgentAccessConfiguratorInterface
{
    /**
     * @var string
     */
    protected const AGENT_ALLOWED_SECURED_PATTERN_REPLACEMENT = '';

    protected AgentConfig $agentConfig;

    public function __construct(AgentConfig $agentConfig)
    {
        $this->agentConfig = $agentConfig;
    }

    public function applyAgentAccessOnSecuredPattern(string $securedPattern): string
    {
        $agentAllowedSecuredPatternList = $this->agentConfig->getAgentAllowedSecuredPatternList();
        if ($agentAllowedSecuredPatternList === []) {
            return $securedPattern;
        }

        foreach ($agentAllowedSecuredPatternList as $allowedSecuredPattern) {
            $securedPattern = str_replace($allowedSecuredPattern, static::AGENT_ALLOWED_SECURED_PATTERN_REPLACEMENT, $securedPattern);
        }

        return $securedPattern;
    }
}
