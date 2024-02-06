<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentSecurityBlockerMerchantPortal\Expander;

use Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer;
use Spryker\Client\AgentSecurityBlockerMerchantPortal\AgentSecurityBlockerMerchantPortalConfig;

class MerchantPortalConfigurationSettingsExpander implements MerchantPortalConfigurationSettingsExpanderInterface
{
    /**
     * @var \Spryker\Client\AgentSecurityBlockerMerchantPortal\AgentSecurityBlockerMerchantPortalConfig
     */
    protected AgentSecurityBlockerMerchantPortalConfig $agentSecurityBlockerMerchantPortalConfig;

    /**
     * @param \Spryker\Client\AgentSecurityBlockerMerchantPortal\AgentSecurityBlockerMerchantPortalConfig $agentSecurityBlockerMerchantPortalConfig
     */
    public function __construct(AgentSecurityBlockerMerchantPortalConfig $agentSecurityBlockerMerchantPortalConfig)
    {
        $this->agentSecurityBlockerMerchantPortalConfig = $agentSecurityBlockerMerchantPortalConfig;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer> $securityBlockerConfigurationSettingsTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer>
     */
    public function expand(array $securityBlockerConfigurationSettingsTransfers): array
    {
        $securityBlockerConfigurationSettingsTransfer = (new SecurityBlockerConfigurationSettingsTransfer())
            ->setTtl($this->agentSecurityBlockerMerchantPortalConfig->getAgentMerchantPortalBlockingTTL())
            ->setBlockFor($this->agentSecurityBlockerMerchantPortalConfig->getAgentMerchantPortalBlockForSeconds())
            ->setNumberOfAttempts($this->agentSecurityBlockerMerchantPortalConfig->getAgentMerchantPortalBlockingNumberOfAttempts());

        $securityBlockerConfigurationSettingsTransfers[$this->agentSecurityBlockerMerchantPortalConfig->getAgentMerchantPortalSecurityBlockerEntityType()] = $securityBlockerConfigurationSettingsTransfer;

        return $securityBlockerConfigurationSettingsTransfers;
    }
}
