<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SecurityBlockerExtension\SecurityBlockerStorageAdapterPluginInterface;
use Spryker\Client\SecurityBlockerRedis\Plugin\SecurityBlocker\RedisSecurityBlockerStorageAdapterPlugin;

class SecurityBlockerDependencyProvider extends AbstractDependencyProvider
{
    public const PLUGIN_SECURITY_BLOCKER_STORAGE_ADAPTER = 'PLUGIN_SECURITY_BLOCKER_STORAGE_ADAPTER';

    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addSecurityBlockerStorageAdapterPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSecurityBlockerStorageAdapterPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SECURITY_BLOCKER_STORAGE_ADAPTER, function () {
            return $this->getSecurityBlockerStorageAdapterPlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\SecurityBlockerExtension\SecurityBlockerStorageAdapterPluginInterface
     */
    protected function getSecurityBlockerStorageAdapterPlugin(): SecurityBlockerStorageAdapterPluginInterface
    {
        return new RedisSecurityBlockerStorageAdapterPlugin();
    }
}
