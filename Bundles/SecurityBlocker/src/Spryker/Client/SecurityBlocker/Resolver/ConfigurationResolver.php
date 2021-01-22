<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Resolver;

use Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer;
use Spryker\Client\SecurityBlocker\SecurityBlockerConfig;

class ConfigurationResolver implements ConfigurationResolverInterface
{
    /**
     * @var \Spryker\Client\SecurityBlocker\SecurityBlockerConfig
     */
    protected $securityBlockerConfig;

    /**
     * @param \Spryker\Client\SecurityBlocker\SecurityBlockerConfig $securityBlockerConfig
     */
    public function __construct(SecurityBlockerConfig $securityBlockerConfig)
    {
        $this->securityBlockerConfig = $securityBlockerConfig;
    }

    /**
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer
     */
    public function getSecurityBlockerConfigurationSettingsForType(string $type): SecurityBlockerConfigurationSettingsTransfer
    {
        $securityConfigurationSettingTransfers = $this->securityBlockerConfig->getSecurityBlockerConfigurationSettings();
        $defaultSecurityBlockerConfigurationSettingsTransfer = $this->securityBlockerConfig
            ->getDefaultSecurityBlockerConfigurationSettings();

        if (empty($securityConfigurationSettingTransfers[$type])) {
            return $defaultSecurityBlockerConfigurationSettingsTransfer;
        }

        foreach ($securityConfigurationSettingTransfers[$type]->toArray() as $property => $value) {
            if ($value) {
                continue;
            }

            $securityConfigurationSettingTransfers[$type]->offsetSet(
                $property,
                $defaultSecurityBlockerConfigurationSettingsTransfer->offsetGet($property)
            );
        }

        return $securityConfigurationSettingTransfers[$type];
    }
}
