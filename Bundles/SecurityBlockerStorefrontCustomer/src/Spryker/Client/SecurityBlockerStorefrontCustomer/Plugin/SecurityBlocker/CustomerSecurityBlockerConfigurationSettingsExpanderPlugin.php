<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerStorefrontCustomer\Plugin\SecurityBlocker;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SecurityBlockerExtension\Dependency\Plugin\SecurityBlockerConfigurationSettingsExpanderPluginInterface;

/**
 * @method \Spryker\Client\SecurityBlockerStorefrontCustomer\SecurityBlockerStorefrontCustomerFactory getFactory()
 * @method \Spryker\Client\SecurityBlockerStorefrontCustomer\SecurityBlockerStorefrontCustomerConfig getConfig()
 * @method \Spryker\Client\SecurityBlockerStorefrontCustomer\SecurityBlockerStorefrontCustomerClientInterface getClient()
 */
class CustomerSecurityBlockerConfigurationSettingsExpanderPlugin extends AbstractPlugin implements SecurityBlockerConfigurationSettingsExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands security blocker configuration settings with customer user settings.
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
            ->expandSecurityBlockerConfigurationsWithCustomerConfiguration($securityBlockerConfigurationSettingsTransfers);
    }
}
