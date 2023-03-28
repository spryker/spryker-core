<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SecurityBlocker\Dependency\Client\SecurityBlockerToRedisClientBridge;

/**
 * @method \Spryker\Client\SecurityBlocker\SecurityBlockerConfig getConfig()
 */
class SecurityBlockerDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_REDIS = 'CLIENT_REDIS';

    /**
     * @var string
     */
    public const PLUGINS_SECURITY_BLOCKER_CONFIGURATION_SETTINGS_EXPANDER = 'PLUGINS_SECURITY_BLOCKER_CONFIGURATION_SETTINGS_EXPANDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addRedisClient($container);
        $container = $this->addSecurityBlockerConfigurationSettingsExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addRedisClient(Container $container): Container
    {
        $container->set(static::CLIENT_REDIS, function (Container $container) {
            return new SecurityBlockerToRedisClientBridge(
                $container->getLocator()->redis()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSecurityBlockerConfigurationSettingsExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SECURITY_BLOCKER_CONFIGURATION_SETTINGS_EXPANDER, function () {
            return $this->getSecurityBlockerConfigurationSettingsExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Client\SecurityBlockerExtension\Dependency\Plugin\SecurityBlockerConfigurationSettingsExpanderPluginInterface>
     */
    protected function getSecurityBlockerConfigurationSettingsExpanderPlugins(): array
    {
        return [];
    }
}
