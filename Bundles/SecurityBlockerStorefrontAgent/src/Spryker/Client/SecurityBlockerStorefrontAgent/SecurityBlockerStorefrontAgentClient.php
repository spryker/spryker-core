<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerStorefrontAgent;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SecurityBlockerStorefrontAgent\SecurityBlockerStorefrontAgentFactory getFactory()
 */
class SecurityBlockerStorefrontAgentClient extends AbstractClient implements SecurityBlockerStorefrontAgentClientInterface
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
    public function expandSecurityBlockerConfigurationsWithAgentConfiguration(array $securityBlockerConfigurationSettingsTransfers): array
    {
        return $this->getFactory()
            ->createAgentConfigurationSettingsExpander()
            ->expand($securityBlockerConfigurationSettingsTransfers);
    }
}
