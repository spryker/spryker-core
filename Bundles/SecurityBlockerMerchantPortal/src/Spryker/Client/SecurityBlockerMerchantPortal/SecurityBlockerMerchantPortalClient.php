<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerMerchantPortal;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalFactory getFactory()
 */
class SecurityBlockerMerchantPortalClient extends AbstractClient implements SecurityBlockerMerchantPortalClientInterface
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
    public function expandSecurityBlockerConfigurationsWithMerchantPortalUserConfiguration(array $securityBlockerConfigurationSettingsTransfers): array
    {
        return $this->getFactory()
            ->createMerchantPortalConfigurationSettingsExpander()
            ->expand($securityBlockerConfigurationSettingsTransfers);
    }
}
