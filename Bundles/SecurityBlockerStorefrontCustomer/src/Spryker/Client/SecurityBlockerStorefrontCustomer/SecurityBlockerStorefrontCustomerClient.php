<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerStorefrontCustomer;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SecurityBlockerStorefrontCustomer\SecurityBlockerStorefrontCustomerFactory getFactory()
 */
class SecurityBlockerStorefrontCustomerClient extends AbstractClient implements SecurityBlockerStorefrontCustomerClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer> $securityBlockerConfigurationSettingsTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer>
     */
    public function expandSecurityBlockerConfigurationsWithCustomerConfiguration(array $securityBlockerConfigurationSettingsTransfers): array
    {
        return $this->getFactory()
            ->createCustomerConfigurationSettingsExpander()
            ->expand($securityBlockerConfigurationSettingsTransfers);
    }
}
