<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerStorefrontCustomer;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SecurityBlockerStorefrontCustomer\Expander\CustomerConfigurationSettingsExpander;
use Spryker\Client\SecurityBlockerStorefrontCustomer\Expander\CustomerConfigurationSettingsExpanderInterface;

/**
 * @method \Spryker\Client\SecurityBlockerStorefrontCustomer\SecurityBlockerStorefrontCustomerConfig getConfig()
 */
class SecurityBlockerStorefrontCustomerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SecurityBlockerStorefrontCustomer\Expander\CustomerConfigurationSettingsExpanderInterface
     */
    public function createCustomerConfigurationSettingsExpander(): CustomerConfigurationSettingsExpanderInterface
    {
        return new CustomerConfigurationSettingsExpander(
            $this->getConfig(),
        );
    }
}
