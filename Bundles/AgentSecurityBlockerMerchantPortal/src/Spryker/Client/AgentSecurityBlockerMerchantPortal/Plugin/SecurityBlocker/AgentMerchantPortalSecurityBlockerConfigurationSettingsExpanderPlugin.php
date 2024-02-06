<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentSecurityBlockerMerchantPortal\Plugin\SecurityBlocker;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SecurityBlockerExtension\Dependency\Plugin\SecurityBlockerConfigurationSettingsExpanderPluginInterface;

/**
 * @method \Spryker\Client\AgentSecurityBlockerMerchantPortal\AgentSecurityBlockerMerchantPortalFactory getFactory()
 * @method \Spryker\Client\AgentSecurityBlockerMerchantPortal\AgentSecurityBlockerMerchantPortalConfig getConfig()
 * @method \Spryker\Client\AgentSecurityBlockerMerchantPortal\AgentSecurityBlockerMerchantPortalClientInterface getClient()
 */
class AgentMerchantPortalSecurityBlockerConfigurationSettingsExpanderPlugin extends AbstractPlugin implements SecurityBlockerConfigurationSettingsExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands security blocker configuration settings with agent merchant portal settings.
     *
     * @api
     *
     * @param array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer> $securityBlockerConfigurationSettingsTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer>
     */
    public function expand(array $securityBlockerConfigurationSettingsTransfers): array
    {
        return $this->getClient()
            ->expandSecurityBlockerConfigurationsWithAgentMerchantPortalConfiguration($securityBlockerConfigurationSettingsTransfers);
    }
}
