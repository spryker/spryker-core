<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerBackoffice\Expander;

use Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer;
use Spryker\Client\SecurityBlockerBackoffice\SecurityBlockerBackofficeConfig;

class BackofficeConfigurationSettingsExpander implements BackofficeConfigurationSettingsExpanderInterface
{
    /**
     * @var \Spryker\Client\SecurityBlockerBackoffice\SecurityBlockerBackofficeConfig
     */
    protected SecurityBlockerBackofficeConfig $securityBlockerBackofficeConfig;

    /**
     * @param \Spryker\Client\SecurityBlockerBackoffice\SecurityBlockerBackofficeConfig $securityBlockerBackofficeConfig
     */
    public function __construct(SecurityBlockerBackofficeConfig $securityBlockerBackofficeConfig)
    {
        $this->securityBlockerBackofficeConfig = $securityBlockerBackofficeConfig;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer> $securityBlockerConfigurationSettingsTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer>
     */
    public function expand(array $securityBlockerConfigurationSettingsTransfers): array
    {
        $securityBlockerConfigurationSettingsTransfer = (new SecurityBlockerConfigurationSettingsTransfer())
            ->setTtl($this->securityBlockerBackofficeConfig->getBackofficeUserBlockingTTL())
            ->setBlockFor($this->securityBlockerBackofficeConfig->getBackofficeUserBlockForSeconds())
            ->setNumberOfAttempts($this->securityBlockerBackofficeConfig->getBackofficeUserBlockingNumberOfAttempts());

        $securityBlockerConfigurationSettingsTransfers[$this->securityBlockerBackofficeConfig->getBackofficeUserSecurityBlockerEntityType()] = $securityBlockerConfigurationSettingsTransfer;

        return $securityBlockerConfigurationSettingsTransfers;
    }
}
