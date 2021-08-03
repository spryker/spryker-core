<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Configurator;

use Spryker\Client\Agent\AgentConfig;

class AgentAccessConfigurator implements AgentAccessConfiguratorInterface
{
    protected const AGENT_ALLOWED_SECURED_PATTERN_REPLACEMENT = '';

    /**
     * @var \Spryker\Client\Agent\AgentConfig
     */
    protected $agentConfig;

    /**
     * @param \Spryker\Client\Agent\AgentConfig $agentConfig
     */
    public function __construct(AgentConfig $agentConfig)
    {
        $this->agentConfig = $agentConfig;
    }

    /**
     * @param string $securedPattern
     *
     * @return string
     */
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
