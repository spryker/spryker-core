<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerStorefrontCustomer\Expander;

use Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer;
use Spryker\Client\SecurityBlockerStorefrontCustomer\SecurityBlockerStorefrontCustomerConfig;

class CustomerConfigurationSettingsExpander implements CustomerConfigurationSettingsExpanderInterface
{
    /**
     * @var \Spryker\Client\SecurityBlockerStorefrontCustomer\SecurityBlockerStorefrontCustomerConfig
     */
    protected SecurityBlockerStorefrontCustomerConfig $securityBlockerStorefrontCustomerConfig;

    /**
     * @param \Spryker\Client\SecurityBlockerStorefrontCustomer\SecurityBlockerStorefrontCustomerConfig $securityBlockerStorefrontCustomerConfig
     */
    public function __construct(SecurityBlockerStorefrontCustomerConfig $securityBlockerStorefrontCustomerConfig)
    {
        $this->securityBlockerStorefrontCustomerConfig = $securityBlockerStorefrontCustomerConfig;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer> $securityBlockerConfigurationSettingsTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer>
     */
    public function expand(array $securityBlockerConfigurationSettingsTransfers): array
    {
        $securityBlockerConfigurationSettingsTransfer = (new SecurityBlockerConfigurationSettingsTransfer())
            ->setTtl($this->securityBlockerStorefrontCustomerConfig->getCustomerBlockingTTL())
            ->setBlockFor($this->securityBlockerStorefrontCustomerConfig->getCustomerBlockForSeconds())
            ->setNumberOfAttempts($this->securityBlockerStorefrontCustomerConfig->getCustomerBlockingNumberOfAttempts());

        $securityBlockerConfigurationSettingsTransfers[$this->securityBlockerStorefrontCustomerConfig->getSecurityBlockerCustomerEntityType()] = $securityBlockerConfigurationSettingsTransfer;

        return $securityBlockerConfigurationSettingsTransfers;
    }
}
