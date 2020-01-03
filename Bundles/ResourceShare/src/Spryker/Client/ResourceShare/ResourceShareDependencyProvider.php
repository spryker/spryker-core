<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ResourceShare\Dependency\Client\ResourceShareToZedRequestClientBridge;

class ResourceShareDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const PLUGINS_AFTER_ZED_RESOURCE_SHARE_ACTIVATOR_STRATEGY = 'PLUGINS_RESOURCE_SHARE_ACTIVATOR_STRATEGY';
    public const PLUGINS_BEFORE_ZED_RESOURCE_SHARE_ACTIVATOR_STRATEGY = 'PLUGINS_BEFORE_ZED_RESOURCE_SHARE_ACTIVATOR_STRATEGY';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addBeforeZedResourceShareActivatorStrategyPlugins($container);
        $container = $this->addAfterZedResourceShareActivatorStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addBeforeZedResourceShareActivatorStrategyPlugins(Container $container): Container
    {
        $container[static::PLUGINS_BEFORE_ZED_RESOURCE_SHARE_ACTIVATOR_STRATEGY] = function () {
            return $this->getBeforeZedResourceShareActivatorStrategyPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addAfterZedResourceShareActivatorStrategyPlugins(Container $container): Container
    {
        $container[static::PLUGINS_AFTER_ZED_RESOURCE_SHARE_ACTIVATOR_STRATEGY] = function () {
            return $this->getAfterZedResourceShareActivatorStrategyPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container[static::CLIENT_ZED_REQUEST] = function (Container $container) {
            return new ResourceShareToZedRequestClientBridge(
                $container->getLocator()->zedRequest()->client()
            );
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareClientActivatorStrategyPluginInterface[]
     */
    protected function getAfterZedResourceShareActivatorStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareClientActivatorStrategyPluginInterface[]
     */
    protected function getBeforeZedResourceShareActivatorStrategyPlugins(): array
    {
        return [];
    }
}
