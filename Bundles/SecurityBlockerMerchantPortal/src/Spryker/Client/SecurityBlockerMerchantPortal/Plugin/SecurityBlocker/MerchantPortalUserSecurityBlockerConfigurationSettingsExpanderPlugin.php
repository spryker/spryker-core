<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerMerchantPortal\Plugin\SecurityBlocker;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SecurityBlockerExtension\Dependency\Plugin\SecurityBlockerConfigurationSettingsExpanderPluginInterface;

/**
 * @method \Spryker\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalFactory getFactory()
 * @method \Spryker\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalConfig getConfig()
 * @method \Spryker\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalClientInterface getClient()
 */
class MerchantPortalUserSecurityBlockerConfigurationSettingsExpanderPlugin extends AbstractPlugin implements SecurityBlockerConfigurationSettingsExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands security blocker configuration settings with merchant portal user settings.
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
            ->expandSecurityBlockerConfigurationsWithMerchantPortalUserConfiguration($securityBlockerConfigurationSettingsTransfers);
    }
}
