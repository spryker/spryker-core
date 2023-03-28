<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerStorefrontAgent\Plugin\SecurityBlocker;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SecurityBlockerExtension\Dependency\Plugin\SecurityBlockerConfigurationSettingsExpanderPluginInterface;

/**
 * @method \Spryker\Client\SecurityBlockerStorefrontAgent\SecurityBlockerStorefrontAgentFactory getFactory()
 * @method \Spryker\Client\SecurityBlockerStorefrontAgent\SecurityBlockerStorefrontAgentConfig getConfig()
 * @method \Spryker\Client\SecurityBlockerStorefrontAgent\SecurityBlockerStorefrontAgentClientInterface getClient()
 */
class AgentSecurityBlockerConfigurationSettingsExpanderPlugin extends AbstractPlugin implements SecurityBlockerConfigurationSettingsExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands security blocker configuration settings with agent settings.
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
            ->expandSecurityBlockerConfigurationsWithAgentConfiguration($securityBlockerConfigurationSettingsTransfers);
    }
}
