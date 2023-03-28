<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerStorefrontAgent\Expander;

use Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer;
use Spryker\Client\SecurityBlockerStorefrontAgent\SecurityBlockerStorefrontAgentConfig;

class AgentConfigurationSettingsExpander implements AgentConfigurationSettingsExpanderInterface
{
    /**
     * @var \Spryker\Client\SecurityBlockerStorefrontAgent\SecurityBlockerStorefrontAgentConfig
     */
    protected SecurityBlockerStorefrontAgentConfig $securityBlockerStorefrontAgentConfig;

    /**
     * @param \Spryker\Client\SecurityBlockerStorefrontAgent\SecurityBlockerStorefrontAgentConfig $securityBlockerStorefrontAgentConfig
     */
    public function __construct(SecurityBlockerStorefrontAgentConfig $securityBlockerStorefrontAgentConfig)
    {
        $this->securityBlockerStorefrontAgentConfig = $securityBlockerStorefrontAgentConfig;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer> $securityBlockerConfigurationSettingsTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer>
     */
    public function expand(array $securityBlockerConfigurationSettingsTransfers): array
    {
        $securityBlockerConfigurationSettingsTransfer = (new SecurityBlockerConfigurationSettingsTransfer())
            ->setTtl($this->securityBlockerStorefrontAgentConfig->getAgentBlockingTTL())
            ->setBlockFor($this->securityBlockerStorefrontAgentConfig->getAgentBlockForSeconds())
            ->setNumberOfAttempts($this->securityBlockerStorefrontAgentConfig->getAgentBlockingNumberOfAttempts());

        $securityBlockerConfigurationSettingsTransfers[$this->securityBlockerStorefrontAgentConfig->getSecurityBlockerAgentEntityType()] = $securityBlockerConfigurationSettingsTransfer;

        return $securityBlockerConfigurationSettingsTransfers;
    }
}
