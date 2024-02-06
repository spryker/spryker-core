<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AgentSecurityBlockerMerchantPortal;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\AgentSecurityBlockerMerchantPortal\Expander\MerchantPortalConfigurationSettingsExpander;
use Spryker\Client\AgentSecurityBlockerMerchantPortal\Expander\MerchantPortalConfigurationSettingsExpanderInterface;

/**
 * @method \Spryker\Client\AgentSecurityBlockerMerchantPortal\AgentSecurityBlockerMerchantPortalConfig getConfig()
 */
class AgentSecurityBlockerMerchantPortalFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\AgentSecurityBlockerMerchantPortal\Expander\MerchantPortalConfigurationSettingsExpanderInterface
     */
    public function createMerchantPortalConfigurationSettingsExpander(): MerchantPortalConfigurationSettingsExpanderInterface
    {
        return new MerchantPortalConfigurationSettingsExpander(
            $this->getConfig(),
        );
    }
}
