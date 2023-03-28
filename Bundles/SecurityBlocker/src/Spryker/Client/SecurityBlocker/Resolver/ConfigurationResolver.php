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
     * @var list<\Spryker\Client\SecurityBlockerExtension\Dependency\Plugin\SecurityBlockerConfigurationSettingsExpanderPluginInterface>
     */
    protected array $securityBlockerConfigurationSettingsExpanderPlugins;

    /**
     * @var \Spryker\Client\SecurityBlocker\SecurityBlockerConfig
     */
    protected SecurityBlockerConfig $securityBlockerConfig;

    /**
     * @param list<\Spryker\Client\SecurityBlockerExtension\Dependency\Plugin\SecurityBlockerConfigurationSettingsExpanderPluginInterface> $securityBlockerConfigurationSettingsExpanderPlugins
     * @param \Spryker\Client\SecurityBlocker\SecurityBlockerConfig $securityBlockerConfig
     */
    public function __construct(
        array $securityBlockerConfigurationSettingsExpanderPlugins,
        SecurityBlockerConfig $securityBlockerConfig
    ) {
        $this->securityBlockerConfigurationSettingsExpanderPlugins = $securityBlockerConfigurationSettingsExpanderPlugins;
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
        $securityConfigurationSettingTransfers = $this->executeSecurityBlockerSettingsConfigurationExpanderPlugins($securityConfigurationSettingTransfers);
        $defaultSecurityBlockerConfigurationSettingsTransfer = $this->securityBlockerConfig->getDefaultSecurityBlockerConfigurationSettings();

        if (empty($securityConfigurationSettingTransfers[$type])) {
            return $defaultSecurityBlockerConfigurationSettingsTransfer;
        }

        foreach ($securityConfigurationSettingTransfers[$type]->toArray() as $property => $value) {
            if ($value) {
                continue;
            }

            $securityConfigurationSettingTransfers[$type]->offsetSet(
                $property,
                $defaultSecurityBlockerConfigurationSettingsTransfer->offsetGet($property),
            );
        }

        return $securityConfigurationSettingTransfers[$type];
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer> $securityConfigurationSettingTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer>
     */
    protected function executeSecurityBlockerSettingsConfigurationExpanderPlugins(array $securityConfigurationSettingTransfers): array
    {
        foreach ($this->securityBlockerConfigurationSettingsExpanderPlugins as $securityBlockerConfigurationSettingsExpanderPlugin) {
            $securityConfigurationSettingTransfers = $securityBlockerConfigurationSettingsExpanderPlugin->expand($securityConfigurationSettingTransfers);
        }

        return $securityConfigurationSettingTransfers;
    }
}
