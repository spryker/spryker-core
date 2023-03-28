<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerMerchantPortal\Expander;

use Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer;
use Spryker\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalConfig;

class MerchantPortalConfigurationSettingsExpander implements MerchantPortalConfigurationSettingsExpanderInterface
{
    /**
     * @var \Spryker\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalConfig
     */
    protected SecurityBlockerMerchantPortalConfig $securityBlockerMerchantPortalConfig;

    /**
     * @param \Spryker\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalConfig $securityBlockerMerchantPortalConfig
     */
    public function __construct(SecurityBlockerMerchantPortalConfig $securityBlockerMerchantPortalConfig)
    {
        $this->securityBlockerMerchantPortalConfig = $securityBlockerMerchantPortalConfig;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer> $securityBlockerConfigurationSettingsTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer>
     */
    public function expand(array $securityBlockerConfigurationSettingsTransfers): array
    {
        $securityBlockerConfigurationSettingsTransfer = (new SecurityBlockerConfigurationSettingsTransfer())
            ->setTtl($this->securityBlockerMerchantPortalConfig->getMerchantPortalUserBlockingTTL())
            ->setBlockFor($this->securityBlockerMerchantPortalConfig->getMerchantPortalUserBlockForSeconds())
            ->setNumberOfAttempts($this->securityBlockerMerchantPortalConfig->getMerchantPortalUserBlockingNumberOfAttempts());

        $securityBlockerConfigurationSettingsTransfers[$this->securityBlockerMerchantPortalConfig->getMerchantPortalUserSecurityBlockerEntityType()] = $securityBlockerConfigurationSettingsTransfer;

        return $securityBlockerConfigurationSettingsTransfers;
    }
}
