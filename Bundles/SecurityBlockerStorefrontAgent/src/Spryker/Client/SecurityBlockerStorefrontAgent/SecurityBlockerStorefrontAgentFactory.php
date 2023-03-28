<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerStorefrontAgent;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SecurityBlockerStorefrontAgent\Expander\AgentConfigurationSettingsExpander;
use Spryker\Client\SecurityBlockerStorefrontAgent\Expander\AgentConfigurationSettingsExpanderInterface;

/**
 * @method \Spryker\Client\SecurityBlockerStorefrontAgent\SecurityBlockerStorefrontAgentConfig getConfig()
 */
class SecurityBlockerStorefrontAgentFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SecurityBlockerStorefrontAgent\Expander\AgentConfigurationSettingsExpanderInterface
     */
    public function createAgentConfigurationSettingsExpander(): AgentConfigurationSettingsExpanderInterface
    {
        return new AgentConfigurationSettingsExpander(
            $this->getConfig(),
        );
    }
}
