<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerMerchantPortal;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SecurityBlockerMerchantPortal\Expander\MerchantPortalConfigurationSettingsExpander;
use Spryker\Client\SecurityBlockerMerchantPortal\Expander\MerchantPortalConfigurationSettingsExpanderInterface;

/**
 * @method \Spryker\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalConfig getConfig()
 */
class SecurityBlockerMerchantPortalFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SecurityBlockerMerchantPortal\Expander\MerchantPortalConfigurationSettingsExpanderInterface
     */
    public function createMerchantPortalConfigurationSettingsExpander(): MerchantPortalConfigurationSettingsExpanderInterface
    {
        return new MerchantPortalConfigurationSettingsExpander(
            $this->getConfig(),
        );
    }
}
