<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerBackoffice;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SecurityBlockerBackoffice\SecurityBlockerBackofficeFactory getFactory()
 */
class SecurityBlockerBackofficeClient extends AbstractClient implements SecurityBlockerBackofficeClientInterface
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
    public function expandSecurityBlockerConfigurationsWithBackofficeUserConfiguration(array $securityBlockerConfigurationSettingsTransfers): array
    {
        return $this->getFactory()
            ->createBackofficeConfigurationSettingsExpander()
            ->expand($securityBlockerConfigurationSettingsTransfers);
    }
}
