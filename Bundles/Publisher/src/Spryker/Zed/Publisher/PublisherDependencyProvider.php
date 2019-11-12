<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Publisher\Dependency\Facade\PublisherToEventBehaviorFacadeBridge;

/**
 * @method \Spryker\Zed\Publisher\PublisherConfig getConfig()
 */
class PublisherDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PUBLISHER_REGISTRY_PLUGINS = 'PUBLISHER_REGISTRY_PLUGINS';
    public const PUBLISHER_DATA_PLUGIN = 'PUBLISHER_DATA_PLUGIN';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $this->addPublisherRegistryPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $this->addEventBehaviorFacade($container);
        $this->addPublisherDataPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPublisherRegistryPlugins(Container $container): Container
    {
        $container->set(static::PUBLISHER_REGISTRY_PLUGINS, function (Container $container) {
            return $this->getPublisherRegistryPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new PublisherToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPublisherDataPlugins(Container $container): Container
    {
        $container->set(static::PUBLISHER_REGISTRY_PLUGINS, function (Container $container) {
            return $this->getPublisherDataPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface[]
     */
    protected function getPublisherRegistryPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherDataPluginInterface[]
     */
    protected function getPublisherDataPlugins(): array
    {
        return [];
    }
}
